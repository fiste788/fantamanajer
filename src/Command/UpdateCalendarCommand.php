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
class UpdateCalendarCommand extends Command
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
        $parser->setDescription('Update season given or current season');

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
        $season = $args->hasArgument('season') ? $this->Matchdays->Seasons->get($args->getArgument('season')) : $this->currentSeason;

        return $this->exec($season, $args, $io);
    }

    public function exec(Season $season, Arguments $args, ConsoleIo $io)
    {

        $matchdays = 38;
        $progress = $io->helper('Progress');
        $progress->init(['total' => $matchdays]);
        for ($matchday = 1; $matchday <= $matchdays; $matchday++) {
            $progress->increment();
            $progress->draw();
            $umc = new UpdateMatchdayCommand();
            $this->executeCommand($umc, ['season' => $season->id, 'matchday' => $matchday, '-n']);
            //$umc->exec($season, $matchday, $args, $io);
        }

        return 1;
    }
}
