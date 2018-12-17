<?php

namespace App\Service;

use App\Model\Entity\Disposition;
use App\Model\Entity\Lineup;
use App\Model\Entity\Matchday;
use Cake\ORM\TableRegistry;

class LineupService
{

    public function duplicate(Lineup $lineup, $teamId, $matchday)
    {
        if ($lineup->team_id == $teamId && $lineup->matchday_id != $matchday->id) {
            $lineup = $this->copy($lineup, $matchday, true, false);
        }
        $lineup->modules = Lineup::$module;
        return $lineup;
    }
    
    public function getEmptyLineup($team)
    {
        $lineup = new Lineup();
        $lineup->team = TableRegistry::get('Teams')->get($team, ['contain' => ['Members' => ['Roles', 'Players']]]);
        $lineup->modules = Lineup::$module;
        return $lineup; 
   }
   
   public function newLineup($team_id, $matchday_id) {
        $lineup = $this->newEntity();
        $lineup->modules = Lineup::$module;
        $lineup->team_id = $team_id;
        $lineup->matchday_id = $matchday_id;
        $lineup->team = $this->Teams->get($team_id, [
            'contain' => [
                'Members' => [
                    'Roles', 'Players'
                ]
            ]
        ]);
        return $lineup;
    }
    
    public function substitute(Lineup $lineup, $old_member_id, $new_member_id)
    {
        foreach ($lineup->dispositions as $key => $disposition) {
            if ($old_member_id == $disposition->id) {
                $lineup->dispositions[$key]->id = $new_member_id;
                $lineup->setDirty('dispositions', true);
            }
        }
        if ($old_member_id == $lineup->captain_id) {
            $lineup->captain_id = $new_member_id;
        }
        if ($old_member_id == $lineup->vcaptain_id) {
            $lineup->vcaptain_id = $new_member_id;
        }
        if ($old_member_id == $lineup->vvcaptain_id) {
            $lineup->vvcaptain_id = $new_member_id;
        }

        return $lineup->isDirty();
    }
    
    /**
     * Return a not saved copy of the entity with the specified matchday
     *
     * @param Matchday $matchday the matchday to use
     * @param bool $isCaptainActive if false empty the captain. Default: true
     * @param bool $cloned if true the lineup was missing. Default true
     * @return Lineup
     */
    public function copy(Lineup $lineup, Matchday $matchday, $isCaptainActive = true, $cloned = true)
    {
        $lineups = TableRegistry::getTableLocator()->get('Lineups');
        $lineupCopy = $lineups->newEntity(
            $lineup->toArray(),
            ['associated' => ['Teams.Championships', 'Dispositions.Members.Ratings']]
        );
        $lineupCopy->id = null;
        $lineupCopy->jolly = null;
        $lineupCopy->cloned = $cloned;
        $lineupCopy->matchday_id = $matchday->id;
        if (!$isCaptainActive) {
            $lineupCopy->captain_id = null;
            $lineupCopy->vcaptain_id = null;
            $lineupCopy->vvcaptain_id = null;
        }
        $lineupCopy->dispositions = array_map(function ($disposition) {
            return $this->reset($disposition);
        }, $lineupCopy->dispositions);

        return $lineupCopy;
    }
    
    /**
     * Reset the entity to default value and new
     *
     * @return Disposition
     */
    private function reset(Disposition $disposition)
    {
        unset($disposition->id);
        unset($disposition->lineup_id);
        $disposition->consideration = 0;

        return $disposition;
    }
}
