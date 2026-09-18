<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Matchday;
use App\Model\Entity\Member;
use App\Model\Entity\Season;
use App\Model\Entity\Player;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Console\ConsoleIo;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Locator\LocatorAwareTrait;

class UpdateMemberService
{
    use LocatorAwareTrait;
    use ServiceAwareTrait;

    /**
     * @var \Cake\Console\ConsoleIo|null
     */
    private ?ConsoleIo $io = null;

    /**
     * Undocumented function
     *
     * @param \Cake\Console\ConsoleIo $io IO
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function __construct(?ConsoleIo $io)
    {
        $this->io = $io;
        $this->loadService('DownloadRatings', [$io]);
    }

    /**
     * Update members
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param string $path Path
     * @return void
     * @throws \Cake\Core\Exception\CakeException
     */
    public function updateMembers(Matchday $matchday, ?string $path = null): void
    {
        /** @var \App\Service\DownloadRatingsService $DownloadRatings */
        $DownloadRatings = $this->loadService(DownloadRatingsService::class);
        $matchdayNumber = $matchday->number;
        if ($this->io != null) {
            $this->io->out('Updating members of matchday ' . $matchdayNumber);
        }
        while ($path == null && $matchdayNumber > -1) {

            /** @var \App\Model\Entity\Matchday $matchday */
            $matchday = $this->fetchTable('Matchdays')->find()->contain(['Seasons'])->where([
                'number' => $matchdayNumber,
                'season_id' => $matchday->season_id,
            ])->first();
            $path = $DownloadRatings->getRatings($matchday);
            $matchdayNumber--;
        }
        if ($path != null && file_exists($path)) {
            /** @var \App\Model\Table\MembersTable $membersTable */
            $membersTable = $this->fetchTable('Members');
            $query = $membersTable->find(
                'list',
                keyField: 'code_gazzetta',
                valueField: function (Member $obj): Member {
                    return $obj;
                },
                contain: ['Players'],
            )->where(['season_id' => $matchday->season_id]);
            /** @var array<\App\Model\Entity\Member> $oldMembers */
            $oldMembers = $query->toArray();
            $newMembers = $DownloadRatings->returnArray($path, ';');
            $buys = [];
            $sells = [];

            $membersToSave = [];
            foreach ($newMembers as $id => $newMember) {
                if (array_key_exists($id, $oldMembers)) {
                    $member = $this->memberTransfert($oldMembers[$id], $newMember[3]);
                    if ($member != null) {
                        $buys[$member->club_id][] = $member;
                        if ($member->isDirty('club_id')) {
                            $sells[(int) $member->getOriginal('club_id')][] = $member;
                        }
                    }
                } else {
                    $member = $this->memberNew($newMember, $matchday->season);
                    $buys[$member->club_id][] = $member;
                }
                if ($member != null) {
                    $membersToSave[] = $member;
                }
            }
            foreach ($oldMembers as $id => $oldMember) {
                if (!array_key_exists($id, $newMembers) && $oldMember->active) {
                    $oldMember->active = false;
                    $membersToSave[] = $oldMember;
                    if ($this->io != null) {
                        $this->io->verbose('Deactivate member ' . $oldMember);
                    }
                    $sells[$oldMember->club_id][] = $oldMember;
                }
            }
            //$this->io->verbose($membersToSave);
            if ($this->io != null) {
                $this->io->out('Savings ' . count($membersToSave) . ' members');
            }
            if ($membersTable->saveMany($membersToSave) == false) {
                $ev = new Event('Fantamanajer.memberTransferts', $this, [
                    'sells' => $sells,
                    'buys' => $buys,
                ]);
                EventManager::instance()->dispatch($ev);
                foreach ($membersToSave as $value) {
                    if (!empty($value->getErrors()) && $this->io != null) {
                        $this->io->err(print_r($value, true));
                        $this->io->err(print_r($value->getErrors(), true));
                    }
                }
            }
        }
    }

    /**
     * Member transfert
     *
     * @param \App\Model\Entity\Member $member Member
     * @param string $clubName Club
     * @return \App\Model\Entity\Member|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Core\Exception\CakeException
     */
    private function memberTransfert(Member $member, string $clubName): ?Member
    {
        $flag = false;
        if (!$member->active) {
            $member->active = true;
            $flag = true;
        }

        /** @var \App\Model\Entity\Club $club */
        $club = $this->fetchTable('Clubs')
            ->find()
            ->where(['name' => ucwords(strtolower(trim($clubName, '"')))])
            ->firstOrFail();
        if ($member->club_id != $club->id) {
            if ($this->io != null) {
                $this->io->verbose('Transfert member ' . $member->player->full_name);
            }
            $member->club = $club;
            $member->active = true;
            $flag = true;
        }

        return $flag ? $member : null;
    }

    /**
     * Member new
     *
     * @param array<string|null> $member Member
     * @param \App\Model\Entity\Season $season Season
     * @return \App\Model\Entity\Member
     * @throws \Cake\Core\Exception\CakeException
     */
    private function memberNew(array $member, Season $season): Member
    {
        $rawClubName = (string) ($member[3] ?? '');
        /** @var \App\Model\Table\ClubsTable $clubsTable */
        $clubsTable = $this->fetchTable('Clubs');
        $club = $clubsTable->findOrCreate(
            ['name' => ucwords(strtolower(trim($rawClubName, '"')))],
            null,
            ['atomic' => false],
        );

        $rawPlayerName = (string) ($member[2] ?? '');
        // Passiamo l'ID del club per aiutare la disambiguazione degli omonimi
        $player = $this->findOrCreatePlayer($rawPlayerName, $club->id);

        if ($this->io != null) {
            $this->io->verbose('Add new member ' . $player->surname . ' ' . $player->name);
        }

        /** @var \App\Model\Table\MembersTable $membersTable */
        $membersTable = $this->fetchTable('Members');

        return $membersTable->newEntity([
            'season_id' => $season->id,
            'code_gazzetta' => $member[0] ?? null,
            'playmaker' => $member[26] ?? false,
            'active' => true,
            'role_id' => ((int) ($member[5] ?? 0)) + 1,
            'club_id' => $club->id,
            'player_id' => $player->id,
        ], ['accessibleFields' => ['*' => true]]);
    }

    /**
     * Trova un giocatore esistente basandosi su cognome, iniziale/nome e squadra,
     * oppure ne crea uno nuovo. Considera il nome solo se presente un punto (es. "M.").
     *
     * @param string|null $fullname Nome completo o cognome con iniziale dal CSV
     * @param int|null $clubId ID della squadra di appartenenza per la disambiguazione
     * @return \App\Model\Entity\Player
     */
    private function findOrCreatePlayer(?string $fullname, ?int $clubId = null): Player
    {
        /** @var \App\Model\Table\PlayersTable $playersTable */
        $playersTable = $this->fetchTable('Players');
        /** @var \App\Model\Table\MembersTable $membersTable */
        $membersTable = $this->fetchTable('Members');

        // Pulizia iniziale con supporto UTF-8
        $rawName = trim((string) $fullname, '" ');
        $rawName = (string) preg_replace('/\s+/u', ' ', $rawName);

        if ($rawName === '') {
            return $playersTable->findOrCreate(
                ['surname' => 'Sconosciuto', 'name' => 'N.'],
                null,
                ['atomic' => false]
            );
        }

        $surname = '';
        $name = '';
        $initialStr = '';

        // Caso 1: "PESSINA M." / "PESSINA Ma." (Iniziale/nome con il punto alla fine)
        if (preg_match('/^(.*?)\s+([\p{L}]{1,3}\.)$/u', $rawName, $matches)) {
            $surname = mb_convert_case(trim($matches[1]), MB_CASE_TITLE, 'UTF-8');
            $initialStr = mb_strtoupper(trim($matches[2], '. '), 'UTF-8');
            $name = mb_convert_case($initialStr, MB_CASE_TITLE, 'UTF-8') . '.';
        }
        // Caso 2: "M. PESSINA" / "Ma. PESSINA" (Iniziale/nome con il punto all'inizio)
        elseif (preg_match('/^([\p{L}]{1,3}\.)\s+(.*?)$/u', $rawName, $matches)) {
            $surname = mb_convert_case(trim($matches[2]), MB_CASE_TITLE, 'UTF-8');
            $initialStr = mb_strtoupper(trim($matches[1], '. '), 'UTF-8');
            $name = mb_convert_case($initialStr, MB_CASE_TITLE, 'UTF-8') . '.';
        }
        // Caso 3: Senza punto (es. "CARLOS AUGUSTO", "VÍTINHA", "DE BRUYNE") -> Tutto nel cognome
        else {
            $surname = mb_convert_case($rawName, MB_CASE_TITLE, 'UTF-8');
            $name = '';
            $initialStr = '';
        }

        // 1. Cerca giocatori esistenti con lo stesso cognome (case-insensitive UTF-8)
        /** @var array<\App\Model\Entity\Player> $candidates */
        $candidates = $playersTable->find()
            ->where(['LOWER(surname)' => mb_strtolower($surname, 'UTF-8')])
            ->toArray();

        if (!empty($candidates)) {
            $matchingCandidates = [];

            foreach ($candidates as $candidate) {
                $candidateNameUpper = mb_strtoupper((string) $candidate->name, 'UTF-8');
                $candidateNameClean = trim($candidateNameUpper, '. ');

                // Verifichiamo la corrispondenza del nome/iniziale
                $isMatch = ($initialStr === '')
                    || str_starts_with($candidateNameClean, $initialStr)
                    || str_starts_with($initialStr, $candidateNameClean);

                if ($isMatch) {
                    $matchingCandidates[] = $candidate;
                }
            }

            // Candidato unico trovato
            if (count($matchingCandidates) === 1) {
                return $matchingCandidates[0];
            }

            // Più candidati (es. Matteo e Marco Pessina)
            if (count($matchingCandidates) > 1) {
                // Priorità 1: Cerca il giocatore appartenente alla squadra specificata
                if ($clubId !== null) {
                    foreach ($matchingCandidates as $candidate) {
                        $hasClubMatch = $membersTable->find()
                            ->select(['id'])
                            ->where([
                                'player_id' => $candidate->id,
                                'club_id' => $clubId,
                            ])
                            ->first() !== null;

                        if ($hasClubMatch) {
                            return $candidate;
                        }
                    }
                }

                // Priorità 2: Match esatto dell'iniziale
                foreach ($matchingCandidates as $candidate) {
                    $candidateNameClean = mb_strtoupper(trim((string) $candidate->name, '. '), 'UTF-8');
                    if ($candidateNameClean === $initialStr) {
                        return $candidate;
                    }
                }

                return $matchingCandidates[0];
            }
        }

        // 2. Se non esiste un giocatore idoneo, crea un nuovo Player
        /** @var \App\Model\Entity\Player $player */
        $player = $playersTable->findOrCreate(
            [
                'surname' => $surname,
                'name' => $name,
            ],
            null,
            ['atomic' => false]
        );

        return $player;
    }
}