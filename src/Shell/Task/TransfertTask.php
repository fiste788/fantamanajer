<?php
namespace App\Shell\Task;

use App\Model\Table\MatchdaysTable;
use Cake\Console\Shell;
use DateTime;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property MatchdaysTable $Matchdays
 * @property \App\Model\Table\SelectionsTable $Selections
 */
class TransfertTask extends Shell
{
    /**
     *
     * @var \App\Model\Entity\Matchday
     */
    private $currentMatchday = null;
    
    /**
     *
     * @var \App\Model\Entity\Season
     */
    private $currentSeason = null;
    
    public function initialize() {
        parent::initialize();
        $this->loadModel('Matchdays');
        $this->loadModel('Selections');
        $this->currentMatchday = $this->Matchdays->findCurrent();
        $this->currentSeason = $this->currentMatchday->season;
    }
    
    public function main()
    {
        $this->doTransfert();
    }
    
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOption('no-commit', ['boolean' => true]);
        /*$parser->addSubcommand('do_transfert', [
            'help' => 'Do transferts from selections'
        ]);*/
        return $parser;
    }
    
    function doTransfert() {
        $selections = $this->Selections->findByMatchdayIdAndProcessed($this->currentMatchday->id, false)
                ->contain(['OldMembers.Players','NewMembers.Players','Teams']);
        $table[] = ['Team', 'New Member','Old Member'];
        if(!$selections->isEmpty()) {
            foreach ($selections as $selection) {
                $selection->processed = true;
                $selection->matchday_id = $this->currentMatchday->id;
                $table[] = [
                    $selection->team->name,
                    $selection->old_member->player->fullName,
                    $selection->new_member->player->fullName, 
                ];
            }
            $this->helper('Table')->output($table);
            if(!$this->param('no-commit')) {
            //if($this->in('Are you sure?', ['y','n'], 'y') == 'y') {
                $this->Selections->saveMany($selections);
                $this->out('Changes committed');
            }
        } else {
            $this->out('No unprocessed selections found');
        }
    }
}
