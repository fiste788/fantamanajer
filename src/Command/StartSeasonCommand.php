<?php

namespace App\Command;

use App\Model\Entity\Season;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use DateTime;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\SelectionsTable $Selections
 */
class StartSeasonCommand extends Command
{
    use CurrentMatchdayTrait;
    use Traits\GazzettaTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Matchdays');
        $this->loadModel('Selections');
        $this->getCurrentMatchday();
    }

    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser->setDescription('Start a new season');

        return $parser;
    }

    /**
     *
     * @return Season
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $season = $this->createSeason();
        if ($season->key_gazzetta == null) {
            $this->getCurrentMatchday();
            if ($this->calculateKey() != '') {
                $this->updateMembers($season, 0);
            }
        } else {
            $io->err('Season for year ' . $season->year . ' already exist');

            $this->abort();
        }
    }

    private function createSeason(ConsoleIo $io)
    {
        $year = date("Y");
        $season = $this->Seasons->find()->where(['year' => $year])->first();
        if ($season == null) {
            $season = $this->Seasons->newEntity(
                [
                'year' => $year,
                'name' => 'Stagione ' . $year . '/' . substr($year + 1, 2, 2)
                ]
            );
            $this->Seasons->saveOrFail($season);
            $io->out('Created new season for year ' . $year);

            $firstMatchday = $this->Matchdays->newEntity(
                [
                'season_id' => $season->id,
                'number' => 0,
                'date' => new DateTime($year . '-08-10 00:00:00')
                ]
            );
            $this->Matchdays->save($firstMatchday);
            $io->out('Updating calendar');
            $command = new UpdateCalendarCommand();
            $command->initialize();
            $command->exec($season, $args, $io);
            $io->out('Creating last matchday');
            $lastMatchday = $this->Matchdays->newEntity(
                [
                'season_id' => $season->id,
                'number' => 39,
                'date' => new DateTime($year + 1 . '-07-31 23:59:59')
                ]
            );
            if ($this->Matchdays->save($lastMatchday)) {
                return $season;
            }
        } else {
            return $season;
        }
    }
}
