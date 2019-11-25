<?php
declare(strict_types=1);

namespace App\Command;

use App\Traits\CurrentMatchdayTrait;
use App\Utility\WebPush\WebPushMessage;
use Cake\Chronos\Chronos;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use GetStream\Stream\Client;
use Minishlink\WebPush\WebPush;

/**
 * @property \App\Model\Table\LineupsTable $Lineups
 * @property \App\Model\Table\TeamsTable $Teams
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class SendMissingLineupNotificationCommand extends Command
{
    use CurrentMatchdayTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Lineups');
        $this->loadModel('Teams');
        $this->loadModel('Matchdays');
        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true,
            'default' => false,
        ]);
        $parser->addOption('force', [
            'short' => 'f',
            'help' => 'Force excecution',
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
        $tomorrow = Chronos::now()->addDay()->second(0);
        if (
            $args->getOption('force') ||
            $this->currentMatchday->date->isWithinNext('30 minutes') ||
            $this->currentMatchday->date->eq($tomorrow)
        ) {
            $io->out('Start');
            $config = Configure::read('GetStream.default');
            $client = new Client($config['appKey'], $config['appSecret']);
            $webPush = new WebPush(Configure::read('WebPush'));
            $teams = $this->Teams->find()
                ->contain(['Users.PushSubscriptions', 'Championships'])
                ->innerJoinWith('Championships')
                ->where(
                    [
                        'season_id' => $this->currentSeason->id,
                        'Teams.id NOT IN' => $this->Lineups->find()->select('team_id')->where([
                            'matchday_id' => $this->currentMatchday->id,
                        ]),
                    ]
                );
            foreach ($teams as $team) {
                $date = $this->currentMatchday->date->toMutable()->subMinutes($team->championship->minute_lineup);
                foreach ($team->user->push_subscriptions as $subscription) {
                    $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                        ->title('Formazione non ancora impostatata')
                        ->body(sprintf(
                            'Ricordati di impostare la formazione per la giornata %d! Hai tempo fino alle %s',
                            $this->currentMatchday->number,
                            $date->toFormattedDateString()
                        ))
                        ->action('Imposta', 'open')
                        ->tag('missing-lineup-' . $this->currentMatchday->number)
                        ->data(['url' => '/teams/' . $team->id . '/lineup/current']);
                    $io->out('Send push notification to ' . $subscription->endpoint);
                    $messageString = json_encode($message);
                    if ($messageString != false) {
                        $webPush->sendNotification($subscription->getSubscription(), $messageString);
                    }
                }
                $io->out('Create activity in notification stream for team ' . $team->id);
                $feed = $client->feed("notification", $team->id);
                $feed->addActivity([
                    'actor' => 'Team:' . $team->id,
                    'verb' => 'missing',
                    'object' => 'Lineup:',
                ]);
            }
            $webPush->flush();
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
