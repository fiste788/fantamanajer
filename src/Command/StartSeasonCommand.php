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
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
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
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
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
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $this->loadService('UpdateMember', [$io]);
        $this->loadService('Rating', [$io]);

        $season = $this->createSeason($io, $args);
        if ($season->key_gazzetta == null || empty($season->key_gazzetta)) {
            $this->getCurrentMatchday();
            if ($this->Rating->calculateKey($season) != '') {
                /** @var \App\Model\Entity\Matchday $firstMatchday */
                $firstMatchday = $this->Matchdays->find()->where([
                    'number' => '0',
                    'season_id' => $season->id,
                ])->first();
                $this->UpdateMember->updateMembers($firstMatchday);
            }

            return CommandInterface::CODE_SUCCESS;
        } else {
            /** @var \App\Model\Entity\Matchday $firstMatchday */
            $firstMatchday = $this->Matchdays->find()->where([
                'number' => '0',
                'season_id' => $season->id,
            ])->first();
            $this->UpdateMember->updateMembers($firstMatchday);
            $io->err('Season for year ' . $season->year . ' already exist');

            $this->abort();
        }

        return CommandInterface::CODE_ERROR;
    }

    /**
     * Create season
     *
     * @param \Cake\Console\ConsoleIo $io Io
     * @param \Cake\Console\Arguments $args Arguments
     * @return \App\Model\Entity\Season
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    private function createSeason(ConsoleIo $io, Arguments $args): Season
    {
        $year = (int)date('Y');

        /** @var \App\Model\Entity\Season|null $season */
        $season = $this->Seasons->find()->where(['year' => $year])->first();
        if ($season == null) {
            $season = $this->Seasons->newEntity(
                [
                    'year' => $year,
                    'name' => 'Stagione ' . $year . '-' . substr((string)($year + 1), 2, 2),
                    'bonus_points' => true,
                ]
            );

            $firstMatchday = $this->Matchdays->newEntity([
                'number' => 0,
                'date' => Chronos::create($year, 8, 20, 0, 0, 0),
            ]);
            $season->matchdays = [$firstMatchday];
            $this->Seasons->saveOrFail($season);
            $io->out('Created new season for year ' . $year);
            //$this->Matchdays->save($firstMatchday);
            $io->out('Updating calendar');
            $command = new UpdateCalendarCommand();
            $command->initialize();
            $command->exec($season, $args, $io);

            $lastMatchday = $this->Matchdays->newEntity([
                'number' => 39,
                'date' => Chronos::create($year + 1, 7, 31, 23, 59, 59),
            ]);
            $season->matchdays = [$lastMatchday];
            $this->Seasons->saveOrFail($season);

            return $season;
        } else {
            return $season;
        }
    }
}
