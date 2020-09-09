<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\Season;
use App\Traits\CurrentMatchdayTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class UpdateCalendarCommand extends Command
{
    use CurrentMatchdayTrait;

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Matchdays');
        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
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
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        /** @var \App\Model\Entity\Season $season */
        $season = $args->hasArgument('season') ?
            $this->Matchdays->Seasons->get($args->getArgument('season')) : $this->currentSeason;

        return $this->exec($season, $args, $io);
    }

    /**
     * Exec
     *
     * @param \App\Model\Entity\Season $season Season
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io Io
     * @return int
     */
    public function exec(Season $season, Arguments $args, ConsoleIo $io): int
    {
        $matchdays = 38;
        /** @var \Cake\Shell\Helper\ProgressHelper $progress */
        $progress = $io->helper('Progress');
        $progress->init(['total' => $matchdays]);
        for ($matchday = 1; $matchday <= $matchdays; $matchday++) {
            $progress->increment();
            $progress->draw();
            $umc = new UpdateMatchdayCommand();
            $this->executeCommand($umc, ['season' => $season->id, 'matchday' => $matchday, '-n']);
            //$umc->exec($season, $matchday, $args, $io);
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
