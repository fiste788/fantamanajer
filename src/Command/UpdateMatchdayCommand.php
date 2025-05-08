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

class UpdateMatchdayCommand extends Command
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
        $parser->addArgument('matchday');
        $parser->setDescription('Update matchday given or current matchday');

        return $parser;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Console\Exception\StopException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $seasonsTable = $this->fetchTable('Seasons');
        /** @var \App\Model\Entity\Season $season */
        $season = $args->hasArgument('season') ?
            $seasonsTable->get((int)$args->getArgument('season')) : $this->currentSeason;
        $matchday = $args->hasArgument('matchday') ?
            (int)$args->getArgument('matchday') : $this->currentMatchday->number;

        return $this->exec($season, $matchday, $args, $io);
    }

    /**
     * Undocumented function
     *
     * @param \App\Model\Entity\Season $season Season
     * @param int $matchdayNumber Matchday
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io Io
     * @return int
     * @throws \Cake\Console\Exception\StopException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function exec(Season $season, int $matchdayNumber, Arguments $args, ConsoleIo $io): int
    {
        /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
        $matchdaysTable = $this->fetchTable('Matchdays');
        /** @var \App\Model\Entity\Matchday|null $matchday */
        $matchday = $matchdaysTable->find()->where(['number' => $matchdayNumber, 'season_id' => $season->id])->first();
        if ($matchday == null) {
            $matchday = $matchdaysTable->newEmptyEntity();
            $matchday->season_id = $season->id;
            $matchday->number = $matchdayNumber;
        }

        $date = (new GetMatchdayScheduleCommand())->exec($season, $matchday, $io);
        if ($date != null && (!$season->key_gazzetta || $date->isFuture())) {
            $noInteraction = $args->getBooleanOption('no-interaction') ?? false;
            $res = $noInteraction || ($io->askChoice(
                'Set ' . $date->format('Y-m-d H:i:s') . ' for matchday ' . $matchday->number,
                ['y', 'n'],
                'y',
            ) == 'y');
            if ($res) {
                $matchday->set('date', $date);
                $matchdaysTable->save($matchday);
                $io->out('Updated matchday ' . $matchday->number . ' with ' . $matchday->date->format('d/m/Y H:i'));
            }
        } else {
            $io->error('Cannot get ' . $matchdayNumber);
            $this->abort();
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
