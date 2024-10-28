<?php
declare(strict_types=1);

namespace App\Service;

use AllowDynamicProperties;
use App\Model\Entity\MembersTeam;
use App\Model\Entity\Transfert;
use ArrayObject;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * @property \App\Service\LineupService $Lineup
 */
#[AllowDynamicProperties]
class TransfertService
{
    use LocatorAwareTrait;

    use ServiceAwareTrait;

    /**
     * Constructor
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->loadService('Lineup');
    }

    /**
     * Substitute members in members team
     *
     * @param \App\Model\Entity\Transfert $transfert The transfert to process
     * @return void
     * @throws \Cake\Core\Exception\CakeException
     */
    public function substituteMembers(Transfert $transfert): void
    {
        $team = $transfert->offsetExists('team') ? $transfert->team : null;
        if ($team == null) {
            /** @var \App\Model\Table\TeamsTable $teamsTable */
            $teamsTable = $this->fetchTable('Teams');
            $team = $teamsTable->get($transfert->team_id);
        }

        /** @var \App\Model\Table\MembersTeamsTable $membersTeamsTable */
        $membersTeamsTable = $this->fetchTable('MembersTeams');
        /** @var \App\Model\Entity\MembersTeam $rec */
        $rec = $membersTeamsTable->find()->innerJoinWith('Teams')->where([
            'member_id' => $transfert->old_member_id,
            'Teams.championship_id' => $team->championship_id,
        ])->first();
        $rec->member_id = $transfert->new_member_id;

        $recs = new ArrayObject();
        $recs->append($rec);

        /** @var \App\Model\Entity\MembersTeam|null $rec2 */
        $rec2 = $membersTeamsTable->find()->innerJoinWith('Teams')->where([
            'member_id' => $transfert->new_member_id,
            'Teams.championship_id' => $team->championship_id,
        ])->first();
        if ($rec2 != null) {
            /** @var \App\Model\Table\TransfertsTable $transfertsTable */
            $transfertsTable = $this->fetchTable('Transferts');
            $rec2->member_id = $transfert->old_member_id;
            $recs->append($rec2);
            $transfert = $transfertsTable->newEntity([
                'team_id' => $rec2->team_id,
                'old_member_id' => $transfert->new_member_id,
                'new_member_id' => $transfert->old_member_id,
                'matchday_id' => $transfert->matchday_id,
                'constrained' => $transfert->constrained,
            ]);
            $transfertsTable->save($transfert, ['associated' => false]);
        }
        $membersTeamsTable->saveMany($recs, ['associated' => false]);
    }

    /**
     * Search old member in lineup and substitute with new
     *
     * @param \App\Model\Entity\Transfert $transfert The transfert
     * @return void
     * @throws \Cake\Core\Exception\CakeException
     */
    public function substituteMemberInLineup(Transfert $transfert): void
    {
        /** @var \App\Model\Table\LineupsTable $lineupsTable */
        $lineupsTable = $this->fetchTable('Lineups');
        /** @var \App\Model\Entity\Lineup|null $lineup */
        $lineup = $lineupsTable->find()
            ->contain(['Dispositions'])
            ->where(['team_id' => $transfert->team_id, 'matchday_id' => $transfert->matchday_id])
            ->first();
        if (
            $lineup != null &&
            $this->Lineup->substitute($lineup, $transfert->old_member_id, $transfert->new_member_id)
        ) {
            $lineupsTable->save($lineup, ['associated' => true]);
        }
    }

    /**
     * Save team member
     *
     * @param \App\Model\Entity\MembersTeam $entity The mermber team
     * @return void
     * @throws \InvalidArgumentException
     * @throws \Cake\Core\Exception\CakeException
     */
    public function saveTeamMember(MembersTeam $entity): void
    {
        /**
         * @psalm-suppress DocblockTypeContradiction
         */
        if ($entity->member == null) {
            /** @var \App\Model\Table\MembersTeamsTable $membersTeamsTable */
            $membersTeamsTable = $this->fetchTable('MembersTeams');
            /** @var \App\Model\Entity\MembersTeam $entity */
            $entity = $membersTeamsTable->loadInto($entity, ['Members']);
        }
        /** @var \App\Model\Entity\Matchday $current */
        $current = $this->fetchTable('Matchdays')->find('current')->first();

        /** @var \App\Model\Table\TransfertsTable $transfertsTable */
        $transfertsTable = $this->fetchTable('Transferts');
        $transfert = $transfertsTable->newEmptyEntity();
        $transfert->team_id = $entity->team_id;
        /**
         * @psalm-suppress DocblockTypeContradiction
         */
        $transfert->constrained = $entity->member == null || !$entity->member->active;
        $transfert->matchday_id = $current->id;
        $transfert->old_member_id = $entity->getOriginal('member_id');
        $transfert->new_member_id = $entity->member_id;
        $transfertsTable->save($transfert);
    }
}
