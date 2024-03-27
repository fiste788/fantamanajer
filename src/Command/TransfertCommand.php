<?php
declare(strict_types=1);

namespace App\Command;

use App\Traits\CurrentMatchdayTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

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
        if ($this->currentMatchday->isDoTransertDay() || $args->getOption('force') == true) {
            $matchday = $this->currentMatchday;
            if ($args->hasArgument('matchday')) {
                $matchdaysTable = $this->fetchTable('Matchdays');
                /** @var \App\Model\Entity\Matchday $matchday */
                $matchday = $matchdaysTable->find()->where([
                    'season_id' => $this->currentSeason->id,
                    'number' => $args->getArgument('matchday'),
                ])->firstOrFail();
            }

            $selectionsTable = $this->fetchTable('Selections');
            $selections = $selectionsTable->find()
                ->contain(['OldMembers.Players', 'NewMembers.Players', 'Teams'])
                ->where([
                    'matchday_id' => $matchday->id,
                    'processed' => false,
                    'Selections.active' => true,
                ])->all();
            $table = [];
            $table[] = ['Team', 'New Member', 'Old Member'];
            if (!$selections->isEmpty()) {
                /** @var array<\App\Model\Entity\Selection> $selectionsArray */
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
                if ($args->getOption('no-commit') == false) {
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
     * @param array<\App\Model\Entity\Selection> $selections Selections
     * @return void
     * @throws \Cake\Core\Exception\CakeException
     */
    private function doTransferts(ConsoleIo $io, array $selections): void
    {
        /** @var \App\Model\Table\SelectionsTable $selectionsTable */
        $selectionsTable = $this->fetchTable('Selections');
        if ($selectionsTable->saveMany($selections) != false) {
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
