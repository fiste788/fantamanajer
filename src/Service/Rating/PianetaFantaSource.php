<?php
declare(strict_types=1);

namespace App\Service\Rating;

use App\Model\Entity\Matchday;
use Cake\Console\ConsoleIo;
use Cake\Http\Client;
use Cake\ORM\Locator\LocatorAwareTrait;
use Symfony\Component\Filesystem\Filesystem;

class PianetaFantaSource implements RatingSourceInterface
{
    use LocatorAwareTrait;

    private const API_URL = 'https://pianetafanta.it/api/voti/squadra';
    private const REFERER_URL = 'https://pianetafanta.it/voti-fantacalcio';
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36';

    private const RUOLO_MAP = [
        'P' => 0,
        'D' => 1,
        'C' => 2,
        'A' => 3,
    ];

    private const BONUS_MALUS = [
        'gf' => 3.0,   // Gol Fatti (GoalReal)
        'gs' => -1.0,  // Gol Subiti (GoalSub)
        'as' => 1.0,   // Assist + Adf
        'au' => -2.0,  // Autorete
        'goalp' => 3.0,   // Rigore Parato (RigorePar)
        'sb' => -3.0,  // Rigore Sbagliato (RigoreSba)
        'amm' => -0.5,  // Ammonizione (Amm)
        'esp' => -1.0,  // Espulsione (Esp)
    ];

    public ?ConsoleIo $io = null;

    public function setIo(?ConsoleIo $io): void
    {
        $this->io = $io;
    }

    public function getRatings(Matchday $matchday, int $offsetGazzetta = 0, bool $forceDownload = false): ?string
    {
        $year = $matchday->season->year;
        $folder = RATINGS_CSV . $year . DS;
        $number = str_pad((string) $matchday->number, 2, '0', STR_PAD_LEFT);
        $pathCsv = "{$folder}Matchday{$number}.csv";

        $filesystem = new Filesystem();

        if ($filesystem->exists($pathCsv) && filesize($pathCsv) > 0 && !$forceDownload) {
            $this->io?->out("File CSV già esistente in {$pathCsv}. Download non necessario.");
            return $pathCsv;
        }

        $this->io?->out("Inizio recupero voti da API PianetaFanta per giornata {$matchday->number}...");

        if ($this->fetchRatingsFromApi($matchday, $pathCsv)) {
            return $pathCsv;
        }

        return null;
    }

    private function fetchRatingsFromApi(Matchday $matchday, string $pathCsv): bool
    {
        // Recupera le squadre dal DB con i relativi membri (giocatori del club)
        $clubsTable = $this->getTableLocator()->get('Clubs');

        /** @var \App\Model\Entity\Club[] */
        $clubs = $clubsTable->find('bySeasonId', season_id: $matchday->season->id)
            ->contain(['Members' => ['Players']])
            ->all();

        $http = new Client([
            'ssl_verify_peer' => false,
            'headers' => [
                'Accept' => '*/*',
                'User-Agent' => self::USER_AGENT,
                'Referer' => self::REFERER_URL,
            ],
        ]);

        $year = $matchday->season->year;
        $stagioneStr = $year . '_' . ($year + 1);
        $matchdayNumber = $matchday->number;

        $csvLines = [];

        foreach ($clubs as $club) {
            $squadraClean = strtoupper(trim((string) $club->name));
            $this->io?->verbose("Download e unione voti per squadra: {$squadraClean}");

            $response = $http->get(self::API_URL, [
                'squadra' => $squadraClean,
                'giornata' => $matchdayNumber,
                'stagione' => $stagioneStr,
            ]);

            // Indicizza i dati tornati dall'API per CodGiocatore
            $apiGiocatoriMap = [];
            if ($response->isOk()) {
                $json = $response->getJson();
                $giocatoriApi = $json['data']['giocatori'] ?? [];
                foreach ($giocatoriApi as $g) {
                    $cod = (int) ($g['CodGiocatore'] ?? 0);
                    if ($cod !== 0) {
                        $apiGiocatoriMap[$cod] = $g;
                    }
                }
            } else {
                $this->io?->err("Errore HTTP {$response->getStatusCode()} per {$squadraClean}");
            }

            $processedCodes = [];

            // 1. Cicla su tutti i giocatori presenti a DB per il club
            if (!empty($club->members)) {
                foreach ($club->members as $member) {
                    $player = $member->player ?? null;
                    $code = $member->code_gazzetta;
                    $nome = $player->name ?? $member->name ?? '';
                    $ruoloRaw = $player->role ?? $member->role ?? 'D';

                    if (isset($apiGiocatoriMap[$code])) {
                        // Giocatore presente nell'API PianetaFanta
                        $g = $apiGiocatoriMap[$code];
                        $stats = $this->parsePlayerStats($g);
                        $quota = (int) ($g['Quota'] ?? 0);
                        // Usa il nome dell'API se disponibile, altrimenti fallback DB
                        if (!empty($g['Nome'])) {
                            $nome = $g['Nome'];
                        }
                    } else {
                        // Giocatore a DB ma assente nella risposta API
                        $stats = $this->createEmptyPlayerStats();
                        $quota = 0;
                    }

                    $csvLines[] = $this->buildCsvLine(
                        $code,
                        $nome,
                        $squadraClean,
                        $this->normalizeRole($ruoloRaw),
                        $stats,
                        $quota
                    );

                    $processedCodes[$code] = true;
                }
            }

            // 2. Aggiunge eventuali giocatori presenti nell'API ma non ancora censiti nel DB locale
            foreach ($apiGiocatoriMap as $code => $g) {
                if (!isset($processedCodes[$code])) {
                    $stats = $this->parsePlayerStats($g);
                    $ruoloStr = strtoupper(trim((string) ($g['Ruolo'] ?? 'D')));

                    $csvLines[] = $this->buildCsvLine(
                        $code,
                        (string) ($g['Nome'] ?? ''),
                        $squadraClean,
                        self::RUOLO_MAP[$ruoloStr] ?? 1,
                        $stats,
                        (int) ($g['Quota'] ?? 0)
                    );
                }
            }

            usleep(150000);
        }

        if (empty($csvLines)) {
            $this->io?->err('Nessun dato recuperato dall\'API o dal DB.');
            return false;
        }

        try {
            $filesystem = new Filesystem();
            $filesystem->dumpFile($pathCsv, implode("\n", $csvLines));
            $this->io?->out("File CSV generato con successo in {$pathCsv}");

            return true;
        } catch (\Exception $e) {
            $this->io?->err('Errore durante il salvataggio del CSV: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Costruisce la riga CSV a 28 colonne.
     *
     * @param int $code
     * @param string $nome
     * @param string $squadra
     * @param int $ruoloId
     * @param array<string, int|float> $stats
     * @param int $quota
     * @return string
     */
    private function buildCsvLine(int $code, string $nome, string $squadra, int $ruoloId, array $stats, int $quota): string
    {
        $out = array_fill(0, 28, '0');

        $out[0] = (string) $code;
        $out[1] = '0';
        $out[2] = '"' . trim($nome) . '"';
        $out[3] = '"' . $squadra . '"';
        $out[4] = '1';                                    // active (sempre 1)
        $out[5] = (string) $ruoloId;                     // ruolo (0=P, 1=D, 2=C, 3=A)
        $out[6] = (string) $stats['valued'];             // valued
        $out[7] = (string) $stats['points'];             // points (Fantavoto)
        $out[10] = (string) $stats['rating'];             // rating (Voto puro)
        $out[11] = (string) $stats['goals'];              // goals
        $out[12] = (string) $stats['goals_against'];      // goals_against
        $out[13] = (string) $stats['goals_victory'];      // goals_victory
        $out[14] = (string) $stats['goals_tie'];          // goals_tie
        $out[15] = (string) $stats['assist'];             // assist
        $out[16] = (string) $stats['amm'];                // yellow_card
        $out[17] = (string) $stats['esp'];                // red_card
        $out[18] = (string) $stats['rigore_par'];         // penalities_scored/parati
        $out[19] = (string) $stats['rigore_sba'];         // penalities_taken/sbagliati
        $out[23] = (string) $stats['present'];            // present
        $out[24] = (string) $stats['regular'];            // regular
        $out[27] = (string) $quota;                       // quotation

        return implode(';', $out);
    }

    /**
     * Genera un set di statistiche vuoto (valued = 0).
     *
     * @return array<string, int|float>
     */
    private function createEmptyPlayerStats(): array
    {
        return [
            'valued' => 0,
            'rating' => 0.0,
            'points' => 0.0,
            'present' => 0,
            'regular' => 0,
            'goals' => 0,
            'goals_against' => 0,
            'goals_victory' => 0,
            'goals_tie' => 0,
            'assist' => 0,
            'amm' => 0,
            'esp' => 0,
            'rigore_par' => 0,
            'rigore_sba' => 0,
            'autorete' => 0,
        ];
    }

    /**
     * Normalizza il ruolo sia da stringa ('P','D','C','A') che da intero.
     *
     * @param mixed $role
     * @return int
     */
    private function normalizeRole(mixed $role): int
    {
        if (is_numeric($role)) {
            return (int) $role;
        }
        $roleStr = strtoupper(trim((string) $role));

        return self::RUOLO_MAP[$roleStr] ?? 1;
    }

    /**
     * Parsing e calcolo delle statistiche/fantavoto dall'API PianetaFanta.
     *
     * @param array<string, mixed> $g
     * @return array<string, int|float>
     */
    private function parsePlayerStats(array $g): array
    {
        $votoRaw = trim((string) ($g['VotoGazzetta'] ?? ''));
        $votoRawNorm = str_replace(',', '.', $votoRaw);

        $ruolo = strtoupper(trim((string) ($g['Ruolo'] ?? 'D')));
        $gol = (int) ($g['GoalReal'] ?? 0);
        $golSub = (int) ($g['GoalSub'] ?? 0);
        $assist = (int) ($g['Assist'] ?? 0) + (int) ($g['Adf'] ?? 0);
        $autorete = (int) ($g['Autorete'] ?? 0);
        $rigorePar = (int) ($g['RigorePar'] ?? 0);
        $rigoreSba = (int) ($g['RigoreSba'] ?? 0);
        $amm = (int) ($g['Amm'] ?? 0);
        $esp = (int) ($g['Esp'] ?? 0);

        $venticinque = (int) ($g['venticinque'] ?? 0);
        $quindici = (int) ($g['quindici'] ?? 0);
        $regular = (int) ($g['Titolare'] ?? 0);
        $minuti = (int) ($g['Minuti'] ?? 0);

        $applyBonusMalus = function (float $baseVote) use ($gol, $golSub, $assist, $autorete, $rigorePar, $rigoreSba, $amm, $esp): float {
            $tot = $baseVote
                + ($gol * self::BONUS_MALUS['gf'])
                + ($golSub * self::BONUS_MALUS['gs'])
                + ($assist * self::BONUS_MALUS['as'])
                + ($autorete * self::BONUS_MALUS['au'])
                + ($rigorePar * self::BONUS_MALUS['goalp'])
                + ($rigoreSba * self::BONUS_MALUS['sb'])
                + ($amm * self::BONUS_MALUS['amm'])
                + ($esp * self::BONUS_MALUS['esp']);

            return round($tot, 2);
        };

        $baseRating = null;
        if (is_numeric($votoRawNorm)) {
            $parsed = (float) $votoRawNorm;
            if ($parsed > 0.0) {
                $baseRating = $parsed;
            }
        }

        if ($baseRating !== null) {
            $valued = 1;
            $rating = $baseRating;
            $points = $applyBonusMalus($baseRating);
        } else {
            $valued = 0;
            $rating = 0.0;
            $points = 0.0;

            if ($ruolo === 'P') {
                if ($venticinque === 1) {
                    $valued = 1;
                    $rating = 6.0;
                    $points = $applyBonusMalus(6.0);
                } elseif ($esp === 1) {
                    $valued = 1;
                    $rating = 4.0;
                    $points = 4.0;
                } elseif ($gol > 0 || $assist > 0 || $rigorePar > 0 || $autorete > 0) {
                    $valued = 1;
                    $rating = 6.0;
                    $points = $applyBonusMalus(6.0);
                }
            } else {
                if ($gol > 0 || $assist > 0) {
                    $valued = 1;
                    $rating = 6.0;
                    $points = $applyBonusMalus(6.0);
                } elseif ($esp > 0) {
                    $valued = 1;
                    $rating = 4.0;
                    $points = 4.0;
                } elseif ($autorete > 0) {
                    $valued = 1;
                    $rating = 6.0;
                    $points = round(6.0 + ($autorete * self::BONUS_MALUS['au']), 2);
                } elseif ($quindici === 1) {
                    $valued = 1;
                    $rating = 6.0;
                    $points = round(6.0 + ($amm * self::BONUS_MALUS['amm']), 2);
                } elseif ($amm > 0) {
                    $valued = 1;
                    $rating = 5.5;
                    $points = 5.5;
                }
            }
        }

        $present = ($regular === 1 || $minuti > 0 || $valued === 1) ? 1 : 0;

        return [
            'valued' => $valued,
            'rating' => $rating,
            'points' => $points,
            'present' => $present,
            'regular' => $regular,
            'goals' => $gol,
            'goals_against' => $golSub,
            'goals_victory' => (int) ($g['GoalDecisivoV'] ?? 0),
            'goals_tie' => (int) ($g['GoalDecisivoP'] ?? 0),
            'assist' => $assist,
            'amm' => $amm,
            'esp' => $esp,
            'rigore_par' => $rigorePar,
            'rigore_sba' => $rigoreSba,
            'autorete' => $autorete,
        ];
    }
}