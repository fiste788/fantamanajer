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
class UpdateMatchdayCommand extends Command
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
        $parser->addArgument('matchday');
        $parser->setDescription('Update matchday given or current matchday');

        return $parser;
    }

    /**
     *
     * @return Season
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $season = $args->hasArgument('season') ? $args->getArgument('season') : $this->currentSeason;
        $matchday = $args->hasArgument('matchday') ? $args->getArgument('matchday') : $this->currentMatchday->number;
        $this->exec($season, $matchday, $args, $io);
    }
    
    /**
     *
     * @param Season $season
     * @param integer $matchdayNumber
     * @param Arguments $args
     * @param ConsoleIo $io
     */
    public function exec(Season $season, $matchdayNumber, Arguments $args, ConsoleIo $io)
    {
        $matchday = $this->Matchdays->findByNumberAndSeasonId($matchdayNumber, $season->id)->first();
        if (is_null($matchday)) {
            $matchday = $this->Matchdays->newEntity();
            $matchday->season_id = $season->id;
            $matchday->number = $matchdayNumber;
        }
        
        $date = (new GetMatchdayScheduleCommand())->exec($season, $matchday, $io);
        if ($date) {
            $res = !$args->getOption('interactive') || ($args->getOption('interactive') && $io->ask("Set " . $date->format("Y-m-d H:i:s") . " for matchday " . $matchday->number, ['y', 'n'], 'y') == 'y');
            if ($res) {
                $matchday->set('date', $date);
                $this->Matchdays->save($matchday);
                $io->out('Updated matchday ' . $matchday->number . ' with ' . $matchday->date->format('d/m/Y H:i'));
            }
        }
    }
}
