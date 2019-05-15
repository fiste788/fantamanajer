<?php

namespace App\Command;

use App\Model\Entity\Season;
use App\Traits\CurrentMatchdayTrait;
use Cake\Chronos\Chronos;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * @property \App\Model\Table\SeasonsTable $Seasons
 */
class StartSeasonCommand extends Command
{
    use CurrentMatchdayTrait;
    use Traits\GazzettaTrait;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Seasons');
        $this->getCurrentMatchday();
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Start a new season');

        return $parser;
    }

    /**
     *
     * @return Season
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $this->startup($args, $io);
        $season = $this->createSeason($io, $args);
        if ($season->key_gazzetta == null || $season->key_gazzetta == '') {
            $this->getCurrentMatchday();
            if ($this->calculateKey($season) != '') {
                $firstMatchday = $this->Matchdays->find()->where([
                    'number' => '0',
                    'season_id' => $season->id
                ])->first();
                $this->updateMembers($firstMatchday);
            }
        } else {
            $io->err('Season for year ' . $season->year . ' already exist');

            $this->abort();
        }
    }

    private function createSeason(ConsoleIo $io, Arguments $args)
    {
        $year = (int)date("Y");
        $season = $this->Seasons->find()->where(['year' => $year])->first();
        if ($season == null) {
            $season = $this->Seasons->newEntity(
                [
                    'year' => $year,
                    'name' => 'Stagione ' . $year . '-' . substr($year + 1, 2, 2),
                    'bonus_points' => true
                ]
            );
            $this->Seasons->saveOrFail($season);
            $io->out('Created new season for year ' . $year);

            $firstMatchday = $this->Seasons->Matchdays->newEntity(
                [
                    'season_id' => $season->id,
                    'number' => 0,
                    'date' => Chronos::create($year, 8, 10, 0, 0, 0)
                ]
            );
            $this->Seasons->Matchdays->save($firstMatchday);
            $io->out('Updating calendar');
            $command = new UpdateCalendarCommand();
            $command->initialize();
            $command->exec($season, $args, $io);
            $io->out('Creating last matchday');
            $lastMatchday = $this->Seasons->Matchdays->newEntity(
                [
                    'season_id' => $season->id,
                    'number' => 39,
                    'date' => Chronos::create($year + 1, 7, 31, 23, 59, 59)
                ]
            );
            if ($this->Seasons->Matchdays->save($lastMatchday)) {
                return $season;
            }
        } else {
            return $season;
        }
    }
}
