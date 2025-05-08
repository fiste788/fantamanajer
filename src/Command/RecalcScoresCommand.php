<?php
declare(strict_types=1);

namespace App\Command;

use AllowDynamicProperties;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Override;

/**
 * @property \App\Service\ComputeScoreService $ComputeScore
 */
#[AllowDynamicProperties]
class RecalcScoresCommand extends Command
{
    use ServiceAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('ComputeScore');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Cake\Console\Exception\StopException
     */
    #[Override]
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $io->out('Finding scores');
        /** @var \App\Model\Table\ScoresTable $scoresTable */
        $scoresTable = $this->fetchTable('Scores');
        /** @var array<\App\Model\Entity\Score> $scores */
        $scores = $scoresTable->find()
            ->contain(['Teams.Championships', 'Matchdays.Seasons'])
            ->where(['Matchdays.season_id' => 17])->all();

        foreach ($scores as $score) {
            $orig = $score->points;
            $this->ComputeScore->exec($score);
            $io->out('Was ' . $orig . ' to ' . $score->points);
        }

        return $scoresTable->saveMany($scores) != false ? CommandInterface::CODE_SUCCESS : CommandInterface::CODE_ERROR;
    }
}
