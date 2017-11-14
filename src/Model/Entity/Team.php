<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * Team Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $photo
 * @property string $photo_dir
 * @property int $photo_size
 * @property string $photo_type
 * @property int $user_id
 * @property User $user
 * @property int $championship_id
 * @property Championship $championship
 * @property Article[] $articles
 * @property Event[] $events
 * @property Lineup[] $lineups
 * @property Score[] $scores
 * @property Selection[] $selections
 * @property Transfert[] $transferts
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \App\Model\Entity\View1MatchdayWin[] $view1_matchday_win
 * @property Member[] $members
 */
class Team extends Entity
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
    
    protected $_virtual = ['photo_url'];
    
    protected function _getPhotoUrl()
    {
        if($this->photo) {
            \Cake\Log\Log::debug($this->photo);
            return Router::url('/files/' . 
                    $this->getSource() . '/' . 
                    $this->id . '/photo/' . 
                    $this->photo, true);
        }
    }
}
