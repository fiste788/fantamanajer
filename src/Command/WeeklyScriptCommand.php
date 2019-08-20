<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\Traits\GazzettaTrait;
use App\Model\Entity\Championship;
use App\Model\Entity\Matchday;
use App\Model\Entity\Team;
use App\Traits\CurrentMatchdayTrait;
use App\Utility\WebPush\WebPushMessage;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Minishlink\WebPush\WebPush;

/**
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Model\Table\RatingsTable $Ratings
 * @property \App\Model\Table\ChampionshipsTable $Championships
 * @property \App\Model\Table\LineupsTable $Lineups
 * @property \App\Service\ComputeScoreService $ComputeScore
 */
class WeeklyScriptCommand extends Command
{
    use CurrentMatchdayTrait;
    use GazzettaTrait;
    use ServiceAwareTrait;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
        $this->loadModel('Points');
        $this->loadModel('Ratings');
        $this->loadModel('Scores');
        $this->loadModel('Championships');
        $this->loadModel('Lineups');
        $this->loadService('ComputeScore');
        $this->getCurrentMatchday();
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addOption(
            'no_send_mail',
            [
                'help' => 'Disable sending summary mails',
                'boolean' => true,
                'short' => 'm',
            ]
        );
        $parser->addOption(
            'no_calc_scores',
            [
                'help' => 'Disable calc of scores',
                'boolean' => true,
                'short' => 's',
            ]
        );
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true,
            'default' => false,
        ]);

        return $parser;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Console\Arguments $args
     * @param \Cake\Console\ConsoleIo $io
     * @return int|null
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $this->startup($args, $io);
        $missingRatings = $this->Matchdays->findWithoutRatings($this->currentSeason);
        foreach ($missingRatings as $key => $matchday) {
            $io->out("Starting decript file day " . $matchday->number);
            $path = $this->getRatings($matchday);
            if ($path != null) {
                $io->out("Updating table players");
                $this->updateMembers($matchday, $path);
                $io->out("Importing ratings");
                $this->importRatings($matchday, $path);
            } else {
                $io->out("Cannot download ratings from gazzetta");
            }
        }
        if (!$args->getOption('no_calc_scores')) {
            $championships = $this->Championships->find()
                ->contain([
                    'Leagues',
                    'Teams' => [
                        'Championships',
                        'EmailNotificationSubscriptions',
                        'PushNotificationSubscriptions',
                        'Users' => ['PushSubscriptions'],
                    ],
                ])
                ->where(['Championships.season_id' => $this->currentSeason->id]);

            $missingScores = $this->Matchdays->findWithoutScores($this->currentSeason)->all();
            foreach ($missingScores as $key => $matchday) {
                if ($this->Ratings->existMatchday($matchday)) {
                    $this->calculatePoints($matchday, $championships, $args, $io);
                    $io->out("Completed succesfully");
                }
            }
        }
    }

    /**
     *
     * @param \App\Model\Entity\Matchday $matchday
     * @param \App\Model\Entity\Championship[] $championships
     */
    protected function calculatePoints(Matchday $matchday, $championships, Arguments $args, ConsoleIo $io)
    {
        $scores = [];
        foreach ($championships as $championship) {
            $io->out("Calculating points of matchday " . $matchday->number . " for league " . $championship->league->name);
            foreach ($championship->teams as $team) {
                $io->out("Elaborating team " . $team->name);
                $scores[$team->id] = $this->ComputeScore->computeScore($team, $matchday);
            }
            $success = $this->Scores->saveMany($scores, [
                'checkRules' => false,
                'associated' => ['Lineups.Dispositions' => ['associated' => false]],
            ]);
            if ($success && !$args->getOption('no_send_mail')) {
                $io->out("Sending mails");
                $this->sendWeeklyMails($matchday, $championship);
                $io->out("Sending notification");
                $this->sendNotifications($matchday, $championship, $scores, $io);
            } elseif (!$success) {
                foreach ($scores as $score) {
                    $io->err(print_r($score->getErrors(), true));
                }
            }
        }
    }

    /**
     *
     * @param \App\Model\Entity\Matchday $matchday
     * @param \App\Model\Entity\Championship $championship
     * @param \App\Model\Entity\Score[] $scores
     * @param \Cake\Console\ConsoleIo $io
     */
    public function sendNotifications(Matchday $matchday, Championship $championship, $scores, ConsoleIo $io)
    {
        $webPush = new WebPush(Configure::read('WebPush'));
        foreach ($championship->teams as $team) {
            if ($team->isPushSubscripted('score')) {
                foreach ($team->user->push_subscriptions as $subscription) {
                    $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                        ->title('Punteggio giornata ' . $matchday->number . ' ' . $team->name)
                        ->body('La tua squadra ha totalizzato un punteggio di ' . $scores[$team->id]->points . ' punti')
                        ->action('Visualizza', 'open')
                        ->tag('926796012340920300')
                        ->data(['url' => '/scores/' . $scores[$team->id]->id]);

                    $io->out("Sending notification to " . $subscription->endpoint);
                    $webPush->sendNotification($subscription->getSubscription(), json_encode($message));
                }
            }
        }
        $webPush->flush();
    }

    public function sendWeeklyMails(Matchday $matchday, Championship $championship)
    {
        $ranking = $this->Scores->find('ranking', ['championship_id' => $championship->id]);
        foreach ($championship->teams as $team) {
            if ($team->isEmailSubscripted('score')) {
                $this->sendPointMail($team, $matchday, $ranking);
            }
        }
    }

    protected function sendPointMail(Team $team, Matchday $matchday, $ranking)
    {
        $details = $this->Lineups->find('details', [
            'matchday_id' => $matchday->id,
            'team_id' => $team->id,
        ])->first();
        $score = $this->Scores->findByMatchdayIdAndTeamId($matchday->id, $team->id)->first();

        $dispositions = null;
        $regulars = null;
        if ($details) {
            $dispositions = $details->dispositions;
            $regulars = array_splice($dispositions, 0, 11);
        }
        $email = new Email(['template' => 'score']);
        $email->setViewVars(
            [
                    'details' => $details,
                    'ranking' => $ranking,
                    'score' => $score,
                    'regulars' => $regulars,
                    'notRegulars' => $dispositions,
                    'baseUrl' => 'https://fantamanajer.it',
                ]
        )
            ->setSubject('Punteggio ' . $team->name . ' giornata ' . $matchday->number . ': ' . $score->points)
            ->setEmailFormat('html')
            ->setTo($team->user->email)
            ->send();
    }
}
