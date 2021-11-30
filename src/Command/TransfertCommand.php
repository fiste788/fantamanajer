<?php
declare(strict_types=1);

namespace App\Command;

use App\Traits\CurrentMatchdayTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\SelectionsTable $Selections
 */
class TransfertCommand extends Command
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
        $this->Matchdays = $this->fetchTable('Matchdays');
        $this->Selections = $this->fetchTable('Selections');
        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addOption('no-commit', [
            'help' => 'Disable commit.',
            'short' => 'c',
            'boolean' => true,
            'default' => false,
        ]);
        $parser->addOption('force', [
            'help' => 'Force the execution',
            'short' => 'f',
            'boolean' => true,
            'default' => false,
        ]);
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true,
            'default' => false,
        ]);
        $parser->addArgument('matchday');

        return $parser;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        if ($this->currentMatchday->isDoTransertDay() || $args->getOption('force')) {
            $matchday = $this->currentMatchday;
            if ($args->hasArgument('matchday')) {
                /** @var \App\Model\Entity\Matchday $matchday */
                $matchday = $this->Matchdays->find()->where([
                    'season_id' => $this->currentSeason->id,
                    'number' => $args->getArgument('matchday'),
                ])->firstOrFail();
            }

            $selections = $this->Selections->find()
                ->contain(['OldMembers.Players', 'NewMembers.Players', 'Teams'])
                ->where([
                    'matchday_id' => $matchday->id,
                    'processed' => false,
                    'Selections.active' => true,
                ])->all();
            $table = [];
            $table[] = ['Team', 'New Member', 'Old Member'];
            if (!$selections->isEmpty()) {
                /** @var \App\Model\Entity\Selection[] $selectionsArray */
                $selectionsArray = $selections->toList();
                foreach ($selectionsArray as $selection) {
                    $selection->processed = true;
                    $selection->matchday_id = $this->currentMatchday->id;
                    $table[] = [
                        $selection->team->name,
                        $selection->old_member->player->full_name,
                        $selection->new_member->player->full_name,
                    ];
                }
                $io->helper('Table')->output($table);
                if (!$args->getOption('no-commit')) {
                    $this->doTransferts($io, $selectionsArray);
                }
            } else {
                $io->out('No unprocessed selections found');
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }

    /**
     * Do transferts
     *
     * @param \Cake\Console\ConsoleIo $io Io
     * @param \App\Model\Entity\Selection[] $selections Selections
     * @return void
     */
    private function doTransferts(ConsoleIo $io, array $selections): void
    {
        if ($this->Selections->saveMany($selections)) {
            $io->out('Changes committed');
        } else {
            $io->out('Error occurred');
            foreach ($selections as $value) {
                if (!empty($value->getErrors())) {
                    $io->error(print_r($value, true));
                    $io->error(print_r($value->getErrors(), true));
                }
            }
        }
    }
}
