<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\Championship;
use App\Model\Entity\Matchday;
use App\Traits\CurrentMatchdayTrait;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\MailerAwareTrait;
use WebPush\Action;
use WebPush\Notification;

/**
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Model\Table\RatingsTable $Ratings
 * @property \App\Model\Table\ChampionshipsTable $Championships
 * @property \App\Model\Table\LineupsTable $Lineups
 * @property \App\Service\ComputeScoreService $ComputeScore
 * @property \App\Service\RatingService $Rating
 * @property \App\Service\DownloadRatingsService $DownloadRatings
 * @property \App\Service\UpdateMemberService $UpdateMember
 * @property \App\Service\PushNotificationService $PushNotification
 * @property \Cake\ORM\Table $Points
 */
class WeeklyScriptCommand extends Command
{
    use CurrentMatchdayTrait;
    use ServiceAwareTrait;
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
        $this->Seasons = $this->fetchTable('Seasons');
        $this->Matchdays = $this->fetchTable('Matchdays');
        $this->Points = $this->fetchTable('Points');
        $this->Ratings = $this->fetchTable('Ratings');
        $this->Scores = $this->fetchTable('Scores');
        $this->Championships = $this->fetchTable('Championships');
        $this->Lineups = $this->fetchTable('Lineups');
        $this->loadService('ComputeScore');
        $this->loadService('PushNotification');
        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addOption('no_send_mail', [
            'help' => 'Disable sending summary mails',
            'boolean' => true,
            'default' => false,
            'short' => 'm',
        ]);
        $parser->addOption('no_calc_scores', [
            'help' => 'Disable calc of scores',
            'boolean' => true,
            'default' => false,
            'short' => 's',
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
        $this->loadService('Rating', [$io]);
        $this->loadService('DownloadRatings', [$io]);
        $this->loadService('UpdateMember', [$io]);

        $missingRatings = $this->Matchdays->findWithoutRatings($this->currentSeason);
        foreach ($missingRatings as $matchday) {
            $io->out('Starting decript file day ' . $matchday->number);
            $path = $this->DownloadRatings->getRatings($matchday);
            if ($path != null) {
                $io->out('Updating table players');
                $this->UpdateMember->updateMembers($matchday, $path);
                $io->out('Importing ratings');
                $this->Rating->importRatings($matchday, $path);
            } else {
                $io->out('Cannot download ratings from gazzetta');
            }
        }
        if (!$args->getOption('no_calc_scores')) {
            /** @var \App\Model\Entity\Championship[] $championships */
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
                ->where(['Championships.season_id' => $this->currentSeason->id])->all();

            $missingScores = $this->Matchdays->findWithoutScores($this->currentSeason);
            foreach ($missingScores as $matchday) {
                if ($this->Ratings->existMatchday($matchday)) {
                    $this->calculatePoints($matchday, $championships, $args, $io);
                    $io->out('Completed succesfully');
                }
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }

    /**
     * Calculate points
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param \App\Model\Entity\Championship[] $championships Championship
     * @param \Cake\Console\Arguments $args Aguments
     * @param \Cake\Console\ConsoleIo $io Io
     * @return void
     */
    protected function calculatePoints(Matchday $matchday, $championships, Arguments $args, ConsoleIo $io): void
    {
        $scores = [];
        foreach ($championships as $championship) {
            $io->out("Calculating points of matchday {$matchday->number} for league {$championship->league->name}");
            foreach ($championship->teams as $team) {
                $io->out('Elaborating team ' . $team->name);
                $scores[$team->id] = $this->ComputeScore->computeScore($team, $matchday);
            }
            $success = $this->Scores->saveMany($scores, [
                'checkRules' => false,
                'associated' => ['Lineups.Dispositions' => ['associated' => false]],
            ]);
            if ($success && !$args->getOption('no_send_mail')) {
                $io->out('Sending mails');
                $this->sendScoreMails($matchday, $championship);
                $io->out('Sending notification');
                $this->sendScoreNotifications($matchday, $championship, $scores, $io);
            } elseif (!$success) {
                foreach ($scores as $score) {
                    $io->err(print_r($score->getErrors(), true));
                }
            }
        }
    }

    /**
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param \App\Model\Entity\Championship $championship Championship
     * @param \App\Model\Entity\Score[] $scores Scores
     * @param \Cake\Console\ConsoleIo $io IO
     * @return void
     * @throws \ErrorException
     */
    public function sendScoreNotifications(
        Matchday $matchday,
        Championship $championship,
        array $scores,
        ConsoleIo $io
    ): void {
        //$webPush = new WebPush((array)Configure::read('WebPush'));
        foreach ($championship->teams as $team) {
            if ($team->isPushSubscripted('score')) {
                $action = [
                    'operation' => 'navigateLastFocusedOrOpen',
                    'url' => "/scores/{$scores[$team->id]->id}",
                ];
                $title = sprintf('Punteggio giornata %d %s', $matchday->number, $team->name);
                $body = sprintf('La tua squadra ha totalizzato un punteggio di %d punti', $scores[$team->id]->points);
                $message = $this->PushNotification->createDefaultMessage($title, $body)
                    ->addAction(Action::create('open', 'Visualizza'))
                    ->withTag("lineup-{$scores[$team->id]->points}")
                    ->withData(['onActionClick' => [
                       'default' => $action,
                       'open' => $action,
                    ]]);

                $notification = Notification::create()
                        ->withTTL(3600)
                        ->withTopic('score')
                        ->withPayload($message->toString());
                foreach ($team->user->push_subscriptions as $subscription) {
                    $io->out('Sending notification to ' . $subscription->endpoint);
                    $this->PushNotification->sendAndRemoveExpired($notification, $subscription);
                }
            }
        }
    }

    /**
     * Send weekly mails
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @param \App\Model\Entity\Championship $championship Championship
     * @return void
     */
    public function sendScoreMails(Matchday $matchday, Championship $championship): void
    {
        $ranking = $this->Scores->find('ranking', ['championship_id' => $championship->id])->toArray();
        foreach ($championship->teams as $team) {
            if ($team->isEmailSubscripted('score')) {
                $details = $this->Lineups->find('details', [
                    'matchday_id' => $matchday->id,
                    'team_id' => $team->id,
                ])->first();

                $score = $this->Scores->find()->where([
                    'matchday_id' => $matchday->id,
                    'team_id' => $team->id,
                ])->first();

                $this->getMailer('WeeklyScript')->send('score', [$team, $matchday, $ranking, $details, $score]);
            }
        }
    }
}
