<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Score Entity.
 *
 * @property int $id
 * @property float $points
 * @property float $real_points
 * @property float $penality_points
 * @property string $penality
 * @property int $team_id
 * @property \App\Model\Entity\Team $team
 * @property int $matchday_id
 * @property \App\Model\Entity\Matchday $matchday
 * @property int $lineup_id
 * @property \App\Model\Entity\Lineup $lineup
 */
class Score extends Entity
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

    /**
     * @return void
     */
    public function compute()
    {
        $championship = $this->team->championship;
        $lineups = TableRegistry::get('Lineups');
        $this->lineup = $lineups->find('last', [
            'matchday' => $this->matchday,
            'team_id' => $this->team->id
        ])->find('withRatings', ['matchday_id' => $this->matchday_id])->first();
        if ($this->lineup == null || ($this->lineup->matchday_id != $this->matchday->id && $championship->points_missed_lineup == 0)) {
            $this->real_points = 0;
            $this->points = 0;
        } else {
            if (!$this->lineup->matchday_id == $this->matchday->id) {
                $this->lineup = $this->lineup->copy($this->matchday, $championship->captain_missed_lineup);
            }
            $this->real_points = $this->lineup->compute();
            $this->points = ($this->lineup->jolly) ? $this->real_points * 2 : $this->real_points;
            if ($championship->points_missed_lineup != 100 && $this->lineup->cloned) {
                $malusPoints = round((($this->points / 100) * (100 - $championship->points_missed_lineup)), 1);
                $mod = ($malusPoints * 10) % 5;
                $this->penality_points = -(($malusPoints * 10) - $mod) / 10;
                $this->penality = 'Formazione non settata';
                $this->points = $this->points - $this->penality_points;
            }
        }
    }
}
