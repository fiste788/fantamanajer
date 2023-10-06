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

class UpdateCalendarCommand extends Command
{
    use CurrentMatchdayTrait;

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function initialize(): void
    {
        parent::initialize();
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
        $parser->addOption('no-future-check', [
            'short' => 'f',
            'help' => 'Disable future check',
            'boolean' => true,
            'default' => false,
        ]);
        $parser->addArgument('season');
        $parser->setDescription('Update season given or current season');

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null The exit code or null for success
     * @throws \Cake\Core\Exception\CakeException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $seaonsTable = $this->fetchTable('Seasons');
        /** @var \App\Model\Entity\Season $season */
        $season = $args->hasArgument('season') ?
            $seaonsTable->get($args->getArgument('season')) : $this->currentSeason;

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
        /** @var \Cake\Command\Helper\ProgressHelper $progress */
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
