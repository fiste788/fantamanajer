<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\Championship;
use App\Model\Entity\Matchday;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\Query;

/**
 * @property \App\Model\Table\ChampionshipsTable $Championships
 * @property \App\Model\Table\TeamsTable $Teams
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class SendLineupsEmailCommand extends Command
{
    use CurrentMatchdayTrait;
    use MailerAwareTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Championships');
        $this->loadModel('Teams');
        $this->loadModel('Matchdays');
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
     * @inheritDoc 
     *
     * @return int|null
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        if ($this->currentMatchday->date->wasWithinLast('59 seconds') || $args->getOption('force')) {
            $championships = $this->Championships->find()
                ->contain(['Teams' => function (Query $q) {
                    return $q->contain(['Users'])
                        ->innerJoinWith('EmailNotificationSubscriptions', function (Query $q) {
                            return $q->where(['name' => 'lineups', 'enabled' => true]);
                        });
                }])->where(['season_id' => $this->currentSeason->id]);
            foreach ($championships->all() as $championship) {
                $this->sendLineupsChampionship($championship, $this->currentMatchday);
                $io->out('Lineups sended to championship ' . $championship->name);
            }
        }

        return 1;
    }

    /**
     * Send lineups
     *
     * @param \App\Model\Entity\Championship $championship Championship
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @return void
     */
    private function sendLineupsChampionship(Championship $championship, Matchday $matchday): void
    {
        $teams = $this->Teams->find()
            ->contain('Lineups', function (Query $q) use ($matchday) {
                return $q->contain([
                    'Dispositions' => ['Members' => ['Clubs', 'Roles', 'Players']],
                ])->where(['matchday_id' => $matchday->id]);
            })
            ->where(['championship_id' => $championship->id]);

        $this->getMailer('WeeklyScript')->send('lineups', [$championship, $matchday, $teams]);
    }
}
