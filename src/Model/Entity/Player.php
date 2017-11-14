<?php
namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\Routing\Router;
use const DS;

/**
 * Player Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property Member[] $members
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \App\Model\Entity\View0Member[] $view0_members
 * @property \App\Model\Entity\View1MembersStat[] $view1_members_stats
 */
class Player extends Entity
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
    
    protected $_virtual = ['photo_url', 'full_name'];
    
    protected function _getPhotoUrl()
    {
        if($this->members) {
            foreach($this->members as $member) {
                if(file_exists(Configure::read('App.imagesPath.players') . 'season-' . $member->season->id . DS . $member->code_gazzetta . '.jpg')) {
                    return Router::url('/img/players/season-' . $member->season->id . '/' . $member->code_gazzetta . '.jpg', true);
                }
            }
        }
    }
    
    protected function _getFullName() {
        return ($this->_properties['name'] != '' ? $this->_properties['name'] . ' ' : '') . $this->_properties['surname'];
    }
}
