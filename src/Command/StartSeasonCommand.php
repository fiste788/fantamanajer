<?php
declare(strict_types=1);

namespace App\Command;

use AllowDynamicProperties;
use App\Model\Entity\Season;
use App\Traits\CurrentMatchdayTrait;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Chronos\Chronos;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Override;

/**
 * @property \App\Service\RatingService $Rating
 * @property \App\Service\UpdateMemberService $UpdateMember
 */
#[AllowDynamicProperties]
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
    #[Override]
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
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
                ],
            );
            $firstAugust = Chronos::create($year, 8, 1);

            /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
            $matchdaysTable = $this->fetchTable('Matchdays');
            $zeroMatchday = $matchdaysTable->newEntity([
                'number' => 0,
                'date' => $firstAugust,
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
                'date' => $firstAugust->addYears(1)->addSeconds(-1),
            ]);
            $season->matchdays = [$lastMatchday];
            $seasonsTable->saveOrFail($season);

            return $season;
        } else {
            return $season;
        }
    }
}
