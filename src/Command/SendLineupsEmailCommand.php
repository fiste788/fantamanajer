<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\Championship;
use App\Model\Entity\Matchday;
use App\Traits\CurrentMatchdayTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\Query\SelectQuery;

class SendLineupsEmailCommand extends Command
{
    use CurrentMatchdayTrait;
    use MailerAwareTrait;

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
        $parser->addOption('force', [
            'help' => 'Force the execution time.',
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

        return $parser;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        if ($this->currentMatchday->date->wasWithinLast('59 seconds') || $args->getOption('force')) {
            $championshipsTable = $this->fetchTable('Championships');
            /** @var array<\App\Model\Entity\Championship> $championships */
            $championships = $championshipsTable->find()
                ->contain([
                    'Teams' => function (SelectQuery $q): SelectQuery {
                        return $q->contain(['Users'])
                            ->innerJoinWith('EmailNotificationSubscriptions', function (SelectQuery $q): SelectQuery {
                                return $q->where(['name' => 'lineups', 'enabled' => true]);
                            });
                    },
                ])->where(['season_id' => $this->currentSeason->id])
                ->all();
            foreach ($championships as $championship) {
                $this->sendLineupsChampionship($championship, $this->currentMatchday);
                $io->out('Lineups sended to championship ' . $championship->id);
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }

    /**
     * Send lineups
     *
     * @param \App\Model\Entity\Championship $championship Championship
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @return void
     * @throws \Cake\Mailer\Exception\MissingActionException
     * @throws \Cake\Mailer\Exception\MissingMailerException
     * @throws \BadMethodCallException
     * @throws \Cake\Core\Exception\CakeException
     */
    private function sendLineupsChampionship(Championship $championship, Matchday $matchday): void
    {
        $teamsTable = $this->fetchTable('Teams');
        $teams = $teamsTable->find()
            ->contain('Lineups', function (SelectQuery $q) use ($matchday): SelectQuery {
                return $q->contain([
                    'Dispositions' => ['Members' => ['Clubs', 'Roles', 'Players']],
                ])->where(['matchday_id' => $matchday->id]);
            })
            ->where(['championship_id' => $championship->id]);

        $this->getMailer('WeeklyScript')->send('lineups', [$championship, $matchday, $teams]);
    }
}
