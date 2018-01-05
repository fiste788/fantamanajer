<?php

namespace App\Shell\Task;

use App\Model\Entity\Championship;
use App\Model\Entity\Matchday;
use App\Model\Entity\Team;
use App\Model\Table\ChampionshipsTable;
use App\Model\Table\LineupsTable;
use App\Model\Table\MatchdaysTable;
use App\Model\Table\RatingsTable;
use App\Model\Table\ScoresTable;
use App\Model\Table\SeasonsTable;
use App\Traits\CurrentMatchdayTrait;
use App\Utility\WebPush\WebPushMessage;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Minishlink\WebPush\WebPush;

/**
 * @property \App\Shell\Task\GazzettaTask $Gazzetta
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Model\Table\RatingsTable $Ratings
 * @property \App\Model\Table\ChampionshipsTable $Championships
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class WeeklyScriptTask extends Shell
{

    public $tasks = [
        'Gazzetta',
    ];

    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
        $this->loadModel('Points');
        $this->loadModel('Ratings');
        $this->loadModel('Scores');
        $this->loadModel('Championships');
        $this->loadModel('Lineups');
        $this->getCurrentMatchday();
    }

    public function main()
    {
        $this->out('Weekly script task');
        $this->weeklyScript();
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand(
            'send_points_mails',
            [
            'help' => 'Send the mails of points'
            ]
        );
        $parser->addOption(
            'no_send_mail',
            [
            'help' => 'Disable sending summary mails',
            'boolean' => true,
            'short' => 'm'
            ]
        );
        $parser->addOption(
            'no_calc_scores',
            [
            'help' => 'Disable calc of scores',
            'boolean' => true,
            'short' => 's'
            ]
        );

        return $parser;
    }

    public function weeklyScript()
    {
        $missingRatings = $this->Matchdays->findWithoutRatings($this->currentSeason);
        foreach ($missingRatings as $key => $matchday) {
            $this->out("Starting decript file day " . $matchday->number);
            $path = $this->Gazzetta->getRatings($matchday->number);
            if ($path != null) {
                $this->out("Updating table players");
                $this->Gazzetta->updateMembers($this->currentSeason, $matchday->number, $path);
                $this->out("Importing ratings");
                $this->Gazzetta->importRatings($matchday->number, $path);
            } else {
                $this->out("Cannot download ratings from gazzetta");
            }
        }
        if (!$this->param('no_calc_scores')) {
            $championships = $this->Championships->find()
                ->contain(['Teams' => ['Users' => ['Subscriptions'], 'Championships'], 'Leagues'])
                ->where(['Championships.season_id' => $this->currentSeason->id]);

            $missingScores = $this->Matchdays->findWithoutScores($this->currentSeason);
            foreach ($missingScores as $key => $matchday) {
                if ($this->Ratings->existMatchday($matchday)) {
                    $this->calculatePoints($matchday, $championships);
                    $this->out("Completed succesfully");
                }
            }
        }
    }

    /**
     *
     * @param Matchday       $matchday
     * @param Championship[] $championships
     */
    protected function calculatePoints(Matchday $matchday, $championships)
    {
        $scores = [];
        foreach ($championships as $championship) {
            $this->out("Calculating points of matchday " . $matchday->number . " for league " . $championship->league->name);
            foreach ($championship->teams as $team) {
                $this->out("Elaborating team " . $team->name);
                $scores[$team->id] = $this->Scores->calculate($team, $matchday);
            }
            if (!$this->param('no_send_mail')) {
                $this->out("Sending mails");
                $this->sendWeeklyMails($matchday, $championship);
                $this->out("Sending notification");
                $this->sendNotifications($matchday, $championship, $scores);
            }
        }
    }

    public function sendNotifications(Matchday $matchday, Championship $championship, $scores)
    {
        $webPush = new WebPush(Configure::read('WebPush'));
        foreach ($championship->teams as $team) {
            foreach ($team->user->subscriptions as $subscription) {
                $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                        ->title('Punteggio giornata ' . $matchday->number . ' ' . $team->name)
                        ->body('La tua squadra ha totalizzato un punteggio di ' . $scores[$team->id] . ' punti')
                        ->action('Visualizza', 'open')
                        ->tag(926796012340920300)
                        ->data(['url' => '/scores/last']);

                $this->out("Sending notification to " . $subscription->endpoint);
                $webPush->sendNotification(
                    $subscription->endpoint,
                    json_encode($message),
                    $subscription->public_key,
                    $subscription->auth_token
                );
            }
        }
        $webPush->flush();
    }

    public function sendWeeklyMails(Matchday $matchday = null, Championship $championship = null)
    {
        $ranking = $this->Scores->findRanking($championship->Id);
        foreach ($championship->teams as $team) {
            if (!empty($team->user->email) && $team->user->active_email) {
                $this->sendPointMail($team, $matchday, $ranking);
            }
        }
    }

    protected function sendPointMail(Team $team, Matchday $matchday, $ranking)
    {
        $details = $this->Lineups->findStatsByMatchdayAndTeam($matchday->id, $team->id);
        $score = $this->Scores->findByMatchdayIdAndTeamId($matchday->id, $team->id)->first();

        $dispositions = null;
        $regulars = null;
        if ($details) {
            $dispositions = $details->dispositions;
            $regulars = array_splice($dispositions, 0, 11);
        }
        $email = new Email();
        $email->setTemplate('score')
            ->setViewVars(
                [
                    'details' => $details,
                    'ranking' => $ranking,
                    'score' => $score,
                    'regulars' => $regulars,
                    'notRegulars' => $dispositions,
                    'baseUrl' => 'https://fantamanajer.it'
                    ]
            )
            ->setSubject('Punteggio ' . $team->name . ' giornata ' . $matchday->number . ': ' . $score->points)
            ->setEmailFormat('html')
            ->setTo($team->user->email)
            ->send();
    }
}
