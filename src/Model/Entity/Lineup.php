<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Lineup Entity
 *
 * @property int $id
 * @property string $module
 * @property bool $jolly
 * @property bool $cloned
 * @property int $captain_id
 * @property int $vcaptain_id
 * @property int $vvcaptain_id
 * @property int $matchday_id
 * @property int $team_id
 *
 * @property Member $member
 * @property \App\Model\Entity\Matchday $matchday
 * @property \App\Model\Entity\Team $team
 * @property \App\Model\Entity\Disposition[] $dispositions
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $old_id
 * @property \App\Model\Entity\Member $captain
 * @property Member $vcaptain
 * @property Member $vvcaptain
 * @property \App\Model\Entity\Score $score
 * @property \App\Model\Entity\Member $v_captain
 * @property \App\Model\Entity\Member $v_v_captain
 */
class Lineup extends Entity
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
        'id' => false
    ];

    /**
     * Fields with the possible modules of a lineup
     *
     * @var array
     */
    public static $module = [
        '1-4-4-2',
        '1-4-3-3',
        '1-4-5-1',
        '1-3-4-3',
        '1-3-5-2',
        '1-5-3-2',
        '1-5-4-1'
    ];

    /**
     * Return a not saved copy of the entity with the specified matchday
     *
     * @param \App\Model\Entity\Matchday $matchday the matchday to use
     * @param bool $isCaptainActive if false empty the captain. Default: true
     * @param bool $cloned if true the lineup was missing. Default true
     * @return Lineup
     */
    public function copy(Matchday $matchday, $isCaptainActive = true, $cloned = true)
    {
        $lineups = TableRegistry::get('Lineups');
        $lineup = $lineups->newEntity(
            $this->toArray(),
            ['associated' => ['Teams.Championships', 'Dispositions.Members.Ratings']]
        );
        $lineup->id = null;
        $lineup->jolly = null;
        $lineup->cloned = $cloned;
        $lineup->matchday_id = $matchday->id;
        if (!$isCaptainActive) {
            $lineup->captain_id = null;
            $lineup->vcaptain_id = null;
            $lineup->vvcaptain_id = null;
        }
        $lineup->dispositions = array_map(function ($disposition) {
            return $disposition->reset();
        }, $lineup->dispositions);

        return $lineup;
    }
    
    public function substitute($old_member_id, $new_member_id) {
        foreach($this->dispositions as $key => $disposition) {
            if($old_member_id == $disposition->id) {
                $this->dispositions[$key]->id = $new_member_id;
                $this->setDirty('dispositions', true);
            }
        }
        if ($old_member_id == $this->captain_id) {
            $this->captain_id = $new_member_id;
        }
        if ($old_member_id == $this->vcaptain_id) {
            $this->vcaptain_id = $new_member_id;
        }
        if ($old_member_id == $this->vvcaptain_id) {
            $this->vvcaptain_id = $new_member_id;
        }
        return $this->isDirty();
    }

    /**
     *
     * @return float
     */
    public function compute()
    {
        $sum = 0;
        $cap = null;
        $entering = [];
        $this->resetDispositions();
        if ($this->team->championship->captain) {
            $cap = $this->getActiveCaptain();
        }
        $notRegular = array_slice($this->dispositions, 11);
        foreach ($this->dispositions as $disposition) {
            if ($disposition->position <= 11) {
                $member = $disposition->member;
                if (!$member->ratings[0]->valued && count($entering) <= 3) {
                    $substitution = $this->substitution($notRegular, $member);
                    if ($substitution != null) {
                        $entering[$substitution] = true;
                    }
                } else {
                    $sum += $disposition->regularize($cap);
                }
            } else {
                if (array_key_exists($disposition->id, $entering)) {
                    $sum += $disposition->regularize($cap);
                }
            }
            $this->setDirty('dispositions', true);
        }

        return $sum;
    }
    
    private function resetDispositions() {
        foreach ($this->dispositions as $key => $disposition) {
            $disposition->consideration = 0;
            $this->disposition[$key] = $disposition;
        }
    }

    /**
     *
     * @param \App\Model\Entity\Disposition[] $notRegular
     * @param \App\Model\Entity\Member $member
     * @return int
     */
    private function substitution(array $notRegular, Member $member)
    {
        foreach ($notRegular as $disposition) {
            $benchwarmer = $disposition->member;
            $rating = $benchwarmer->ratings[0];
            if (($member->role_id == $benchwarmer->role_id) && ($rating->valued)) {
                return $disposition->id;
            }
        }

        return null;
    }

    /**
     *
     * @return int
     */
    private function getActiveCaptain()
    {
        $captains = [$this->captain_id, $this->vcaptain_id, $this->vvcaptain_id];
        foreach ($captains as $cap) {
            if ($cap) {
                $dispositions = array_filter(
                    $this->dispositions,
                    function ($value) use ($cap) {
                        return $value->member_id == $cap;
                    }
                );
                $disposition = array_shift($dispositions);
                if ($disposition && $disposition->member->ratings[0]->present) {
                    return $cap;
                }
            }
        }

        return null;
    }
}
