<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\MembersTeam;
use App\Model\Entity\Transfert;
use Cake\Datasource\ModelAwareTrait;

/**
 * @property \App\Model\Table\TeamsTable $Teams
 * @property \App\Model\Table\MembersTeamsTable $MembersTeams
 * @property \App\Model\Table\TransfertsTable $Transferts
 * @property \App\Model\Table\LineupsTable $Lineups
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Service\LineupService $Lineup
 */
class TransfertService
{
    use ModelAwareTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadModel('Teams');
        $this->loadModel('MembersTeams');
        $this->loadModel('Transferts');
        $this->loadModel('Lineups');
        $this->loadModel('Matchdays');
    }

    /**
     * Substitute members in members team
     *
     * @param \App\Model\Entity\Transfert $transfert The transfert to process
     * @return void
     */
    public function substituteMembers(Transfert $transfert)
    {
        $team = $transfert->team;
        if (!$team) {
            $team = $this->Teams->get($transfert->team_id);
        }
        $rec = $this->MembersTeams->find()->innerJoinWith('Teams')->where([
            'member_id' => $transfert->old_member_id,
            'Teams.championship_id' => $team->championship_id,
        ])->first();
        $rec->member_id = $transfert->new_member_id;
        $recs[] = $rec;
        $rec2 = $this->MembersTeams->find()->innerJoinWith('Teams')->where([
            'member_id' => $transfert->new_member_id,
            'Teams.championship_id' => $team->championship_id,
        ])->first();
        if ($rec2) {
            $rec2->member_id = $transfert->old_member_id;
            $recs[] = $rec2;
            $transfert = $this->Transferts->newEntity([
                'team_id' => $rec2->team_id,
                'old_member_id' => $transfert->new_member_id,
                'new_member_id' => $transfert->old_member_id,
                'matchday_id' => $transfert->matchday_id,
                'constrained' => $transfert->constrained,
            ]);
            $this->Transferts->save($transfert, ['associated' => false]);
        }
        $this->MembersTeams->saveMany($recs, ['associated' => false]);
    }

    /**
     * Search old member in lineup and substitute with new
     *
     * @param \App\Model\Entity\Transfert $transfert The transfert
     * @return void
     */
    public function substituteMemberInLineup(Transfert $transfert)
    {
        $lineup = $this->Lineups->find()
            ->contain(['Dispositions'])
            ->where(['team_id' => $transfert->team_id, 'matchday_id' => $transfert->matchday_id])
            ->first();
        if ($lineup && $this->Lineup->substitute($lineup, $transfert->old_member_id, $transfert->new_member_id)) {
            $this->Lineups->save($lineup, true);
        }
    }

    /**
     * Save team member
     *
     * @param \App\Model\Entity\MembersTeam $entity The mermber team
     * @return void
     */
    public function saveTeamMember(MembersTeam $entity)
    {
        if (!$entity->member) {
            $entity = $this->MembersTeams->loadInto($entity, ['Members']);
        }
        $transfert = $this->Transferts->newEntity();
        $transfert->team_id = $entity->team_id;
        $transfert->constrained = !$entity->member->active;
        $transfert->matchday_id = $this->Matchdays->find('current')->first()->id;
        $transfert->old_member_id = $entity->getOriginal('member_id');
        $transfert->new_member_id = $entity->member_id;
        $this->Transferts->save($transfert);
    }
}
