<?php

namespace App\Command;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class UpdateCalendarCommand extends Command
{
    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Matchdays');
        $this->getCurrentMatchday();
    }

    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true,
            'default' => false
        ]);
        $parser->addArgument('season');
        $parser->setDescription('Update season given or current season');

        return $parser;
    }

    /**
     *
     * @return Season
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        if (!$args->hasArgument('season')) {
            $season = $this->currentSeason;
        }
        $this->exec($season, $args, $io);
    }
    
    public function exec(Season $season, Arguments $args, ConsoleIo $io)
    {
        $umc = new UpdateMatchdayCommand();
        $umc->initialize();
        $matchdays = 38;
        $progress = $io->helper('Progress');
        $progress->init(['total' => $matchdays]);
        for ($matchday = 1; $matchday <= $matchdays; $matchday++) {
            $progress->increment();
            $progress->draw();
            $umc->exec($season, $matchday, $args, $io);
        }
    }
    
}
