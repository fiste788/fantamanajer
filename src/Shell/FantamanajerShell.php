<?php
namespace App\Shell;

use App\Shell\Task\WeeklyScriptTask;
use Cake\Console\ConsoleOutput;
use Cake\Console\Shell;
use Cake\Utility\Inflector;

/**
 * @property WeeklyScriptTask $WeeklyScript
 * @property Task\GazzettaTask $Gazzetta
 * @property Task\MatchdayTask $Matchday
 * @property Task\TransfertTask $Transfert
 */
class FantamanajerShell extends Shell
{
    public $tasks = [
        'Gazzetta',
        'WeeklyScript',
        'Matchday',
        'Transfert'
    ];
    
    public function main()
    {
        $this->_io->outputAs(ConsoleOutput::COLOR);
    }
    
    public function startSeason() {
        $season = $this->Matchday->startNewSeason();
        if($season->key_gazzetta == null) {
            $this->Gazzetta->getCurrentMatchday();
            if($this->Gazzetta->calculateKey() != '') {
                $this->Gazzetta->updateMembers($season, 0);
            }
        } else {
            $this->err('Season for year ' . $season->year . ' already exist');
            return null;
        }
    }
    
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('start_season', [
            'help' => 'If the season for current year doesn\'t exist create one and try to update members'
        ]);
        foreach ($this->_taskMap as $task => $config) {
            $taskParser = $this->{$task}->getOptionParser();
            $parser->addSubcommand(Inflector::underscore($task), [
                'help' => $taskParser->getDescription(),
                'parser' => $taskParser
            ]);
        }
        return $parser;
    }
    
}
