<?php
namespace App\Shell;

use App\Shell\Task\WeeklyScriptTask;
use Cake\Console\ConsoleOutput;
use Cake\Console\Shell;
use Cake\Utility\Inflector;

/**
 * @property WeeklyScriptTask $WeeklyScript
 * @property Task\GazzettaTask $Gazzetta
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
    
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
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
