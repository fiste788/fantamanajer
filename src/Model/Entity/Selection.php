<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Selection Entity.
 *
 * @property int $id
 * @property boolean $active
 * @property boolean $processed
 * @property int $team_id
 * @property \App\Model\Entity\Team $team
 * @property int $matchday_id
 * @property \App\Model\Entity\Matchday $matchday
 * @property int $old_member_id
 * @property int $new_member_id
 * @property \App\Model\Entity\Member $oldMember
 * @property \App\Model\Entity\Member $newMember
 */
class Selection extends Entity
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
    
    public function toTransfert(\Cake\ORM\Table $transfertsTable) {
        $transfert = $transfertsTable->newEntity();
        $transfert->team_id = $this->team_id;
        $transfert->matchday_id = $this->matchday_id;
        $transfert->old_member_id = $this->old_member_id;
        $transfert->new_member_id = $this->new_member_id;
        return $transfert;
    }
    
    public function isMemberAlreadySelected() {
        $team = TableRegistry::get('Teams')->get($this->team_id);
        $selection = $this->find()
                ->where([
                    'new_member_id' => $this->new_member_id,
                    'championship_id' => $team->championship_id
                ]);
        return !$selection->isEmpty();
    }
}
