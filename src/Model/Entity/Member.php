<?php
namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\Routing\Router;
use const DS;

/**
 * Member Entity.
 *
 * @property int $id
 * @property int $code_gazzetta
 * @property boolean $playmaker
 * @property boolean $active
 * @property int $player_id
 * @property Player $player
 * @property int $role_id
 * @property Role $role
 * @property int $club_id
 * @property Club $club
 * @property int $season_id
 * @property Season $season
 * @property Disposition[] $dispositions
 * @property Rating[] $ratings
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \App\Model\Entity\View0MembersOnlyStat[] $view0_members_only_stats
 * @property Team[] $teams
 */
class Member extends Entity
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
    
    protected $_virtual = ['photo_url', 'background_url'];
    
    protected function _getPhotoUrl()
    {
        if(file_exists(Configure::read('App.imagesPath.players') . 'season-' . $this->season_id . DS . $this->code_gazzetta . '.jpg')) {
            return Router::url('/img/players/season-' . $this->season_id . '/' . $this->code_gazzetta . '.jpg', true);
        }
    }
    
    protected function _getBackgroundUrl()
    {
        if(file_exists(Configure::read('App.imagesPath.clubs') . $this->club_id . DS . 'background' . DS . $this->club_id . '.jpg')) {
            return Router::url('/img/Clubs/' . $this->club_id . '/background/' . $this->club_id . '.jpg', true);
        }
    }
    
}
