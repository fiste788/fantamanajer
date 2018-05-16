<?php
namespace App\Model\Entity;

use Cake\I18n\Time;
use Cake\ORM\Entity;

/**
 * Matchday Entity.
 *
 * @property int $id
 * @property int $number
 * @property \Cake\I18n\FrozenTime $date
 * @property int $season_id
 * @property \App\Model\Entity\Season $season
 * @property \App\Model\Entity\Article[] $articles
 * @property \App\Model\Entity\Lineup[] $lineups
 * @property \App\Model\Entity\Rating[] $ratings
 * @property \App\Model\Entity\Score[] $scores
 * @property \App\Model\Entity\Transfert[] $transferts
 */
class Matchday extends Entity
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

    public function isWeeklyScriptDay()
    {
        return $this->date->diffInHours(new Time(), true) > 48;
    }

    public function isDoTransertDay()
    {
        return $this->date->isToday();
    }

    public function isSendMailDay()
    {
        return $this->date->isWithinNext('10 minutes');
    }
}
