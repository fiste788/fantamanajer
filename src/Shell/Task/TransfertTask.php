<?php
namespace App\Shell\Task;

use App\Model\Table\MatchdaysTable;
use App\Model\Table\SelectionsTable;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Shell;

/**
 * @property MatchdaysTable $Matchdays
 * @property SelectionsTable $Selections
 */
class TransfertTask extends Shell
{
    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Matchdays');
        $this->loadModel('Selections');
        $this->getCurrentMatchday();
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

    public function doTransfert()
    {
        if ($this->currentMatchday->isDoTransertDay()) {
            $selections = $this->Selections->findByMatchdayIdAndProcessed($this->currentMatchday->id, false)
                ->contain(['OldMembers.Players', 'NewMembers.Players', 'Teams']);
            $table[] = ['Team', 'New Member', 'Old Member'];
            if (!$selections->isEmpty()) {
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
                if (!$this->param('no-commit')) {
                    //if($this->in('Are you sure?', ['y','n'], 'y') == 'y') {
                    $this->Selections->saveMany($selections);
                    $this->out('Changes committed');
                }
            } else {
                $this->out('No unprocessed selections found');
            }
        }
    }
}
