<?php

namespace App\Service;

use App\Model\Entity\MembersTeam;
use App\Model\Entity\Transfert;
use App\Model\Table\LineupsTable;
use App\Model\Table\MatchdaysTable;
use App\Model\Table\MembersTeamsTable;
use App\Model\Table\TeamsTable;
use App\Model\Table\TransfertsTable;
use Cake\Datasource\ModelAwareTrait;

/**
 * @property TeamsTable $Teams
 * @property MembersTeamsTable $MembersTeams
 * @property TransfertsTable $Transferts
 * @property LineupsTable $Lineups
 * @property MatchdaysTable $Matchdays
 * @property LineupService $Lineup
 */
class TransfertService
{

    use ModelAwareTrait;

    public function __construct()
    {
        $this->loadModel('Teams');
        $this->loadModel('MembersTeams');
        $this->loadModel('Transferts');
        $this->loadModel('Lineups');
        $this->loadModel('Matchdays');
    }

    public function substituteMembers(Transfert $transfert)
    {
        $team = $transfert->team;
        if (!$team) {
            $team = $this->Teams->get($transfert->team_id);
        }
        $rec = $this->MembersTeams->find()->innerJoinWith('Teams')->where([
                'member_id' => $transfert->old_member_id,
                'Teams.championship_id' => $team->championship_id
            ])->first();
        $rec->member_id = $transfert->new_member_id;
        $recs [] = $rec;
        $rec2 = $this->MembersTeams->find()->innerJoinWith('Teams')->where([
                'member_id' => $transfert->new_member_id,
                'Teams.championship_id' => $team->championship_id
            ])->first();
        if ($rec2) {
            $rec2->member_id = $transfert->old_member_id;
            $recs [] = $rec2;
            $transfert = $this->Transferts->newEntity([
                'team_id' => $rec2->team_id,
                'old_member_id' => $transfert->new_member_id,
                'new_member_id' => $transfert->old_member_id,
                'matchday_id' => $transfert->matchday_id,
                'constrained' => $transfert->constrained
            ]);
            $this->Transferts->save($transfert, ['associated' => false]);
        }
        $this->MembersTeams->saveMany($recs, ['associated' => false]);
    }

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

    public function saveTeamMember(MembersTeam $entity)
    {
        if (!$entity->member) {
            $entity = $this->loadInto($entity, ['Members']);
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
