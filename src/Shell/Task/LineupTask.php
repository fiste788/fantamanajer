<?php

namespace App\Shell\Task;

use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Minishlink\WebPush\WebPush;

/**
 * @property \App\Model\Table\ChampionshipsTable $Championships
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\LineupsTable $Lineups
 * @property \App\Model\Table\TeamsTable $Teams
 */
class LineupTask extends Shell
{

    use CurrentMatchdayTrait;

    /**
     *
     * @var WebPush
     */
    protected $webPush;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Championships');
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
        $this->loadModel('Users');
        $this->loadModel('Lineups');
        $this->loadModel('Teams');
        $this->getCurrentMatchday();
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('send_email');
        $parser->addOption('force', ['boolean' => true]);

        return $parser;
    }

    public function sendEmail()
    {
        if ($this->currentMatchday->date->isWithinNext('1 minutes') || $this->param('force')) {
            $championships = $this->Championships->find()
                ->contain(['Teams.Users'])
                ->where(['season_id' => $this->currentSeason->id]);
            foreach ($championships as $championship) {
                $matchday = $this->currentMatchday;
                $teams = $this->Teams->find()
                    ->contain('Lineups', function(\Cake\ORM\Query $q) use($matchday) {
                        return $q->contain(['Dispositions' => ['Members'=> ['Clubs', 'Roles', 'Players']]])
                            ->where(['matchday_id' => $matchday->id]);
                    })
                    ->where(['championship_id' => $championship->id]);
                $addresses = \Cake\Utility\Hash::extract($championship->teams, '{*}.user.email');
                $addresses = "stefano788@gmail.com";
                $email = new Email();
                $email->setTemplate('lineups')
                    ->setViewVars(
                        [
                            'teams' => $teams,
                            'baseUrl' => 'https://fantamanajer.it'
                        ]
                    )
                    ->setSubject('Formazioni giornata ' . $this->currentMatchday->number)
                    ->setEmailFormat('html')
                    ->setBcc($addresses)
                    ->send();
            }
        }
    }
}
