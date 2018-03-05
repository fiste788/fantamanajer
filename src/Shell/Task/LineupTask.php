<?php

namespace App\Shell\Task;

use App\Model\Entity\Championship;
use App\Model\Entity\Matchday;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Shell;
use Cake\Mailer\Email;
use Cake\ORM\Query;
use Cake\Utility\Hash;

/**
 * @property \App\Model\Table\ChampionshipsTable $Championships
 * @property \App\Model\Table\TeamsTable $Teams
 */
class LineupTask extends Shell
{

    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Championships');
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
        $date = $this->currentMatchday->date;
        if ($date->isWithinNext('1 minutes') || 
            $date->wasWithinLast('1 minutes') || 
            $this->param('force')) {
            $championships = $this->Championships->find()
                ->contain(['Teams' => function(Query $q) {
                    return $q->contain(['Users'])
                        ->innerJoinWith('EmailSubscriptions', function(Query $q) {
                            return $q->where(['lineup' => true]);
                    });
                }])->where(['season_id' => $this->currentSeason->id]);
            foreach ($championships as $championship) {
                $this->sendLineupsChampionship($championship, $this->currentMatchday);
            }
        }
    }

    private function sendLineupsChampionship(Championship $championship, Matchday $matchday)
    {
        $teams = $this->Teams->find()
            ->contain('Lineups', function(Query $q) use($matchday) {
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
