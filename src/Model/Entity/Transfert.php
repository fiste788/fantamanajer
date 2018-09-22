<?php
namespace App\Model\Entity;

use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Transfert Entity.
 *
 * @property int $id
 * @property int $old_member_id
 * @property int $new_member_id
 * @property Member $member
 * @property int $team_id
 * @property Team $team
 * @property int $matchday_id
 * @property Matchday $matchday
 * @property bool $constrained
 * @property Member $old_member
 * @property Member $new_member
 */
class Transfert extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
    
    public function substituteMembers()
    {
        $team = $this->team;
        if(!$team) {
            $teams = TableRegistry::getTableLocator()->get('Teams');
            $team = $teams->get($this->team_id);
        }
        $members = TableRegistry::getTableLocator()->get('MembersTeams');
        $rec = $members->find()->innerJoinWith('Teams')->where([
            'member_id' => $this->old_member_id,
            'Teams.championship_id' => $team->championship_id
            ])->first();
        $rec->member_id = $this->new_member_id;
        $recs [] = $rec;
        $rec2 = $members->find()->innerJoinWith('Teams')->where([
            'member_id' => $this->new_member_id,
            'Teams.championship_id' => $team->championship_id
        ])->first();
        if($rec2) {
            $rec2->member_id = $this->old_member_id;
            $recs [] = $rec2;
            $transferts = TableRegistry::getTableLocator()->get('Transferts');
            $transfert = $transferts->newEntity([
                'team_id' => $rec2->team_id,
                'old_member_id' => $this->new_member_id,
                'new_member_id' => $this->old_member_id,
                'matchday_id' => $this->matchday_id,
                'constrained' => $this->constrained
            ]);
            $transferts->save($transfert, ['associated' => false]);
        }
        $members->saveMany($recs, ['associated' => false]);
    }
}
