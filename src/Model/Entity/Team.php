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
 * @property \App\Model\Entity\User $user
 * @property int $championship_id
 * @property \App\Model\Entity\Championship $championship
 * @property \App\Model\Entity\Article[] $articles
 * @property \App\Model\Entity\Event[] $events
 * @property \App\Model\Entity\Lineup[] $lineups
 * @property \App\Model\Entity\Score[] $scores
 * @property \App\Model\Entity\Selection[] $selections
 * @property \App\Model\Entity\Transfert[] $transferts
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \App\Model\Entity\View1MatchdayWin[] $view1_matchday_win
 * @property \App\Model\Entity\Member[] $members
 * @property int $old_id
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
        if ($this->photo) {
            return Router::url(
                '/files/' .
                    $this->getSource() . '/' .
                    $this->id . '/photo/' .
                $this->photo,
                true
            );
        }
    }
}
