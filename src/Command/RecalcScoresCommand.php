<?php
declare(strict_types=1);

namespace App\Command;

use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;

/**
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Service\ComputeScoreService $ComputeScore
 */
class RecalcScoresCommand extends Command
{
    use ServiceAwareTrait;

    /**
     * @inheritDoc
     *
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Scores');
        $this->loadService('ComputeScore');
    }

    /**
     * @inheritDoc
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Cake\Console\Exception\StopException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $io->out('Finding scores');
        /** @var \App\Model\Entity\Score[] $scores */
        $scores = $this->Scores->find()
            ->contain(['Teams.Championships','Matchdays.Seasons'])
            ->where(['Matchdays.season_id' => 17])->all();

        foreach ($scores as $score) {
            $orig = $score->points;
            $this->ComputeScore->exec($score);
            $io->out('Was ' . $orig . " to " . $score->points);
        }

        return $this->Scores->saveMany($scores) ? CommandInterface::CODE_SUCCESS : CommandInterface::CODE_ERROR;
    }
}
