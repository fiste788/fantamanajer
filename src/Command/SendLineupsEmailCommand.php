<?php

namespace App\Command;

use App\Model\Entity\Championship;
use App\Model\Entity\Matchday;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\Email;
use Cake\ORM\Query;
use Cake\Utility\Hash;

/**
 * @property \App\Model\Table\ChampionshipsTable $Championships
 * @property \App\Model\Table\TeamsTable $Teams
 */
class SendLineupsEmailCommand extends Command
{

    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Championships');
        $this->loadModel('Teams');
        $this->getCurrentMatchday();
    }

    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser->addOption('force', [
            'help' => 'Force the execution time.',
            'short' => 'f',
            'boolean' => true
        ]);
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true,
            'default' => false
        ]);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $date = $this->currentMatchday->date;
        if ($date->wasWithinLast('59 seconds') || $args->getOption('force')) {
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
    }

    private function sendLineupsChampionship(Championship $championship, Matchday $matchday)
    {
        $teams = $this->Teams->find()
            ->contain('Lineups', function (Query $q) use ($matchday) {
                return $q->contain([
                    'Dispositions' => ['Members' => ['Clubs', 'Roles', 'Players']]
                ])->where(['matchday_id' => $matchday->id]);
            })
            ->where(['championship_id' => $championship->id]);
        $addresses = Hash::extract($championship->teams, '{*}.user.email');
        //$addresses = "stefano788@gmail.com";
        $email = new Email();
        $email->setTemplate('lineups')
            ->setViewVars(
                [
                    'teams' => $teams,
                    'baseUrl' => 'https://fantamanajer.it'
                ]
            )
            ->setSubject('Formazioni giornata ' . $matchday->number)
            ->setEmailFormat('html')
            ->setBcc($addresses)
            ->send();
    }
}
