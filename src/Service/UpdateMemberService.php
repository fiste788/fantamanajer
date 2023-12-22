<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Matchday;
use App\Model\Entity\Member;
use App\Model\Entity\Season;
use Cake\Console\ConsoleIo;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Locator\LocatorAwareTrait;
use League\Container\ContainerAwareTrait;

class UpdateMemberService
{
    use LocatorAwareTrait;
    use ContainerAwareTrait;

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
        $DownloadRatings = $this->getContainer()->get(DownloadRatingsService::class);
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
                contain: ['Players']
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
                            $sells[(int)$member->getOriginal('club_id')][] = $member;
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
            if (!$membersTable->saveMany($membersToSave)) {
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
     * @param array<string> $member Member
     * @param \App\Model\Entity\Season $season Season
     * @return \App\Model\Entity\Member
     * @throws \Cake\Core\Exception\CakeException
     */
    private function memberNew(array $member, Season $season): Member
    {
        $esprex = "/[A-Z']*\s?[A-Z']{2,}/";
        $fullname = trim($member[2], '"');
        preg_match($esprex, $fullname, $ass);
        $surname = ucwords(strtolower((!empty($ass) ? $ass[0] : $fullname)));
        $name = ucwords(strtolower(trim(substr($fullname, strlen($surname)))));
        //$queryPlayer = $this->Players->find()->where();

        /** @var \App\Model\Table\PlayersTable $playersTable */
        $playersTable = $this->fetchTable('Players');
        $player = $playersTable->findOrCreate([
            'surname' => $surname,
            'name' => $name,
        ], null, ['atomic' => false]);

        /** @var \App\Model\Table\ClubsTable $clubsTable */
        $clubsTable = $this->fetchTable('Clubs');
        $club = $clubsTable->findOrCreate(
            ['name' => ucwords(strtolower(trim($member[3], '"')))],
            null,
            ['atomic' => false]
        );
        if ($this->io != null) {
            $this->io->verbose('Add new member ' . $surname . ' ' . $name);
        }

        /** @var \App\Model\Table\MembersTable $membersTable */
        $membersTable = $this->fetchTable('Members');

        return $membersTable->newEntity([
            'season_id' => $season->id,
            'code_gazzetta' => $member[0],
            'playmaker' => $member[26],
            'active' => true,
            'role_id' => ((int)$member[5]) + 1,
            'club_id' => $club->id,
            'player_id' => $player->id,
        ], ['accessibleFields' => ['*' => true]]);
    }
}
