<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use App\Traits\CurrentMatchdayTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\I18n\DateTime;
use DateTimeInterface;
use DateTimeZone;
use Override;
use Symfony\Component\DomCrawler\Crawler;
use function Cake\Core\toString;

class GetMatchdayScheduleCommand extends Command
{
    use CurrentMatchdayTrait;

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();
        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        parent::buildOptionParser($parser);
        $parser->addArgument('matchday');
        $parser->addArgument('season');

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null The exit code or null for success
     * @throws \Cake\Core\Exception\CakeException
     */
    #[Override]
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        /** @var \App\Model\Table\SeasonsTable $seasonsTable */
        $seasonsTable = $this->fetchTable('Seasons');
        $season = $args->getArgument('season') != null ?
            $seasonsTable->get($args->getArgument('season')) : $this->currentSeason;
        if (!$args->hasArgument('matchday')) {
            $matchday = $this->currentMatchday;
        } else {
            /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
            $matchdaysTable = $this->fetchTable('Matchdays');
            /** @var \App\Model\Entity\Matchday|null $matchday */
            $matchday = $matchdaysTable->find()->where([
                'number' => $args->getArgument('matchday'),
                'season_id' => $season->id,
            ])->first();
        }

        return $matchday && $this->exec($season, $matchday, $io) ?
            CommandInterface::CODE_SUCCESS : CommandInterface::CODE_ERROR;
    }

    /**
     * Exec
     *
     * @param \App\Model\Entity\Season $season Season
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param \Cake\Console\ConsoleIo $io Io
     * @return \Cake\I18n\DateTime|false|null
     * @throws \Cake\Console\Exception\StopException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function exec(Season $season, Matchday $matchday, ConsoleIo $io): DateTime|false|null
    {
        $client = new Client([
            'host' => 'www.legaseriea.it',
            'timeout' => 30,
            'redirect' => true,
        ]);

        // 1. Estrazione ID Stagione dall'HTML
        $responseHome = $client->get('/serie-a/calendario-risultati', [], [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'application/json',
                'Origin' => 'https://www.legaseriea.it',
                'Referer' => 'https://www.legaseriea.it/',
            ]
        ]);

        $html = $responseHome->getStringBody();
        $cleanHtml = stripslashes($html);

        // Supponiamo che $seasonName sia "2024/2025" o "2025/2026"
        $seasonName = $this->getSeasonName($season); // Questo potresti passarlo come parametro


        // Se il titolo apparisse PRIMA dell'ID nel tuo HTML, invertiamo l'ordine:
        $patternAlternative = '/\\\\?"title\\\\?":\\\\?"' . preg_quote($seasonName, '/') . '\\\\?".*?\\\\?"seasonId\\\\?":\\\\?"(serie-a::Football_Season::[a-z0-9]+)\\\\?"/s';

        if (preg_match($patternAlternative, $html, $matches)) {
            $seasonId = $matches[1];
            $io->success("Trovato ID per la stagione $seasonName: $seasonId");
        } else {
            $io->error("Impossibile trovare l'ID per la stagione: $seasonName");
            return false;
        }


        // 2. Chiamata all'API SDP Matchdays
        $apiUrl = "https://api-sdp.legaseriea.it/v1/serie-a/football/seasons/" . urlencode($seasonId) . "/matchdays?locale=it-IT";
        $io->info($apiUrl);
        $apiResponse = $client->get($apiUrl);
        if (!$apiResponse->isOk()) {
            $io->error('Errore durante la chiamata all\'API SDP.');
            return false;
        }

        $json = $apiResponse->getJson();
        $matchdays = $json['matchdays'] ?? []; // La chiave corretta è 'matchdays'
        $matchDayId = $matchdays[$matchday->number - 1]['matchSetId'] ?? null;

        $matchesUrl = "https://api-sdp.legaseriea.it/v1/serie-a/football/seasons/" .
            urlencode($seasonId) .
            "/matches?matchDayId=" . urlencode($matchDayId) . "&locale=it-IT";

        $resMatches = $client->get($matchesUrl);

        if (!$resMatches->isOk()) {
            $io->error("Impossibile recuperare i match per la giornata.");
            return false;
        }

        $data = $resMatches->getJson();
        $matches = $data['matches'] ?? [];

        if (empty($matches)) {
            $io->error("Nessun match trovato per questa giornata.");
            return false;
        }

        // 2. Troviamo la data minima (il primo calcio d'inizio ufficiale)
        $earliestDate = null;

        foreach ($matches as $match) {
            $currentMatchDate = $match['matchDateUtc'] ?? null;

            if ($currentMatchDate) {
                if ($earliestDate === null || $currentMatchDate < $earliestDate) {
                    $earliestDate = $currentMatchDate;
                }
            }
        }

        if (!$earliestDate) {
            $io->error("Nessuna data valida trovata nei match.");
            return false;
        }

        // 3. Risultato finale
        $timezone = (string) Configure::read('App.defaultTimezone', 'Europe/Rome');
        // Creiamo l'oggetto Cake\I18n\DateTime partendo dalla stringa UTC
// Cake lo parserizza automaticamente riconoscendo lo 'Z' finale come UTC
        $finalDate = new \Cake\I18n\DateTime($earliestDate);

        // Cambiamo la Timezone. 
// Attenzione: se il metodo restituisce un oggetto "Frozen" (immutabile), 
// devi riassegnare la variabile: $finalDate = $finalDate->setTimezone(...)
        $finalDate = $finalDate->setTimezone($timezone);

        $io->success("Data convertita per Cake: " . $finalDate->format('Y-m-d H:i:s'));

        return $finalDate;
    }

    private function getSeasonName(Season $season): string
    {
        // Supponiamo che $season->name arrivi come "2025/26"
        $seasonName = $season->year . "/" . ($season->year + 1);
        return $seasonName;
    }
}
