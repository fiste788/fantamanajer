<?php
namespace App\Shell;

use App\Shell\Task\DownloadPhotosTask;
use App\Shell\Task\GazzettaTask;
use App\Shell\Task\MatchdayTask;
use App\Shell\Task\PushNotificationTask;
use App\Shell\Task\TransfertTask;
use App\Shell\Task\UserTask;
use App\Shell\Task\WeeklyScriptTask;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOutput;
use Cake\Console\Shell;
use Cake\Utility\Inflector;

/**
 * @property \App\Shell\Task\WeeklyScriptTask $WeeklyScript
 * @property \App\Shell\Task\GazzettaTask $Gazzetta
 * @property \App\Shell\Task\MatchdayTask $Matchday
 * @property \App\Shell\Task\TransfertTask $Transfert
 * @property \App\Shell\Task\DownloadPhotosTask $DownloadPhotos
 * @property UserTask $UserTask
 * @property PushNotificationTask $PushNotificationTask
 * @property \App\Shell\Task\UserTask $User
 * @property \App\Shell\Task\PushNotificationTask $PushNotification
 * @property \App\Shell\Task\LineupTask $Lineup
 */
class FantamanajerShell extends Shell
{
    public $tasks = [
        'Gazzetta',
        'WeeklyScript',
        'Matchday',
        'Transfert',
        'DownloadPhotos',
        'User',
        'PushNotification',
        'Lineup'
    ];

    public function main()
    {
        $this->_io->outputAs(ConsoleOutput::COLOR);
    }

    public function startup(): void
    {
        parent::startup();
        if ($this->param('no-interaction')) {
            $this->interactive = false;
        }
    }

    public function startSeason()
    {
        $season = $this->Matchday->startNewSeason();
        if ($season->key_gazzetta == null) {
            $this->Gazzetta->getCurrentMatchday();
            if ($this->Gazzetta->calculateKey() != '') {
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
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true
        ]);
        $parser->addSubcommand(
            'start_season',
            [
            'help' => 'If the season for current year doesn\'t exist create one and try to update members'
            ]
        );
        foreach ($this->_taskMap as $task => $config) {
            $taskParser = $this->{$task}->getOptionParser();
            $parser->addSubcommand(
                Inflector::underscore($task),
                [
                'help' => $taskParser->getDescription(),
                'parser' => $taskParser
                ]
            );
        }

        return $parser;
    }
}
