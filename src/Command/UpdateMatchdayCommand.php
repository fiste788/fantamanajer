<?php
declare(strict_types=1);

namespace App\Command;

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

    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Matchdays');
        $this->getCurrentMatchday();
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true,
            'default' => false,
        ]);
        $parser->addArgument('season');
        $parser->addArgument('matchday');
        $parser->setDescription('Update matchday given or current matchday');

        return $parser;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Console\Arguments $args
     * @param \Cake\Console\ConsoleIo $io
     * @return int|null
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $season = $args->hasArgument('season') ? $this->Matchdays->Seasons->get((int)$args->getArgument('season')) : $this->currentSeason;
        $matchday = $args->hasArgument('matchday') ? (int)$args->getArgument('matchday') : $this->currentMatchday->number;
        return $this->exec($season, $matchday, $args, $io);
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Season $season
     * @param int $matchdayNumber
     * @param \Cake\Console\Arguments $args
     * @param \Cake\Console\ConsoleIo $io
     * @return int
     */
    public function exec(Season $season, int $matchdayNumber, Arguments $args, ConsoleIo $io): ?int
    {
        $matchday = $this->Matchdays->findByNumberAndSeasonId($matchdayNumber, $season->id)->first();
        if (is_null($matchday)) {
            $matchday = $this->Matchdays->newEmptyEntity();
            $matchday->season_id = $season->id;
            $matchday->number = $matchdayNumber;
        }

        $date = (new GetMatchdayScheduleCommand())->exec($season, $matchday, $io);
        if ($date != null && $date->isFuture()) {
            $res = $args->getOption('no-interaction') || (!$args->getOption('no-interaction') && $io->askChoice("Set " . $date->format("Y-m-d H:i:s") . " for matchday " . $matchday->number, ['y', 'n'], 'y') == 'y');
            if ($res) {
                $matchday->set('date', $date);
                $this->Matchdays->save($matchday);
                $io->out('Updated matchday ' . $matchday->number . ' with ' . $matchday->date->format('d/m/Y H:i'));
            }
        } else {
            $io->error('Cannot get ' . $matchday);
            $this->abort();
        }
        return 1;
    }
}
