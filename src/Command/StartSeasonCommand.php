<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\Season;
use App\Traits\CurrentMatchdayTrait;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Chronos\Chronos;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * @property \App\Service\RatingService $Rating
 * @property \App\Service\UpdateMemberService $UpdateMember
 */
class StartSeasonCommand extends Command
{
    use CurrentMatchdayTrait;
    use ServiceAwareTrait;

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
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Start a new season');

        return $parser;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Console\Exception\StopException
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $this->loadService('UpdateMember', [$io]);
        $this->loadService('Rating', [$io]);

        /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
        $matchdaysTable = $this->fetchTable('Matchdays');

        $season = $this->createSeason($io, $args);
        if ($season->key_gazzetta == null) {
            $this->getCurrentMatchday();
            if ($this->Rating->calculateKey($season) != '') {
                /** @var \App\Model\Entity\Matchday $firstMatchday */
                $firstMatchday = $matchdaysTable->find()->where([
                    'number' => '0',
                    'season_id' => $season->id,
                ])->first();
                $this->UpdateMember->updateMembers($firstMatchday);
            }

            return CommandInterface::CODE_SUCCESS;
        } else {
            $matchdaysTable = $this->fetchTable('Matchdays');
            /** @var \App\Model\Entity\Matchday $firstMatchday */
            $firstMatchday = $matchdaysTable->find()->where([
                'number' => '0',
                'season_id' => $season->id,
            ])->first();
            $this->UpdateMember->updateMembers($firstMatchday);
            $io->err('Season for year ' . $season->year . ' already exist');

            $this->abort();
        }
    }

    /**
     * Create season
     *
     * @param \Cake\Console\ConsoleIo $io Io
     * @param \Cake\Console\Arguments $_args Arguments
     * @return \App\Model\Entity\Season
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    private function createSeason(ConsoleIo $io, Arguments $_args): Season
    {
        $year = (int)date('Y');

        /** @var \App\Model\Table\SeasonsTable $seasonsTable */
        $seasonsTable = $this->fetchTable('Seasons');
        /** @var \App\Model\Entity\Season|null $season */
        $season = $seasonsTable->find()->where(['year' => $year])->first();
        if ($season == null) {
            $season = $seasonsTable->newEntity(
                [
                    'year' => $year,
                    'name' => 'Stagione ' . $year . '-' . substr((string)($year + 1), 2, 2),
                    'bonus_points' => true,
                ]
            );

            /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
            $matchdaysTable = $this->fetchTable('Matchdays');
            $zeroMatchday = $matchdaysTable->newEntity([
                'number' => 0,
                'date' => Chronos::now()->endOfDay(),
            ]);
            $season->matchdays = [$zeroMatchday];
            $seasonsTable->saveOrFail($season);
            $io->out('Created new season for year ' . $year);
            $io->out('Updating calendar');
            $command = new UpdateCalendarCommand();
            $command->initialize();
            $upcArgs = new Arguments([], ['no-future-check' => true], []);
            $command->exec($season, $upcArgs, $io);

            $lastMatchday = $matchdaysTable->newEntity([
                'number' => 39,
                'date' => Chronos::create($year + 1, 7, 31, 23, 59, 59),
            ]);
            $season->matchdays = [$lastMatchday];

            /** @var \App\Model\Entity\Matchday $firstMatchday */
            $firstMatchday = $matchdaysTable->find()->where(['number' => 1, 'season_id' => $season->id])->first();
            /** @var \App\Model\Entity\Matchday $zeroMatchday */
            $zeroMatchday = $matchdaysTable->find()->where(['number' => 0, 'season_id' => $season->id])->first();
            $zeroMatchday->date = $firstMatchday->date->subDays(7)->startOfWeek()->startOfDay();
            $matchdaysTable->saveOrFail($zeroMatchday);
            $seasonsTable->saveOrFail($season);

            return $season;
        } else {
            return $season;
        }
    }
}
