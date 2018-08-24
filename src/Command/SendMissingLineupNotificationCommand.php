<?php

namespace App\Command;

use App\Traits\CurrentMatchdayTrait;
use App\Utility\WebPush\WebPushMessage;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use GetStream\Stream\Client;
use Minishlink\WebPush\WebPush;

/**
 * @property \App\Model\Table\LineupsTable $Lineups
 * @property \App\Model\Table\TeamsTable $Teams
 */
class SendMissingLineupNotificationCommand extends Command
{
    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Lineups');
        $this->loadModel('Teams');
        $this->getCurrentMatchday();
    }

    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true,
            'default' => false
        ]);
        $parser->addOption('force', [
            'short' => 'f',
            'help' => 'Force excecution',
            'boolean' => true,
            'default' => false
        ]);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        if ($this->currentMatchday->date->isWithinNext('30 minutes') || $args->getOption('force')) {
            $config = Configure::read('GetStream.default');
            $client = new Client($config['appKey'], $config['appSecret']);
            $webPush = new WebPush(Configure::read('WebPush'));
            $teams = $this->Teams->find()
                ->contain(['Users.PushSubscriptions'])
                ->innerJoinWith('Championships')
                ->where(
                    [
                        'season_id' => $this->currentSeason->id,
                        'Teams.id NOT IN' => $this->Lineups->find()->select('team_id')->where(['matchday_id' => $this->currentMatchday->id])
                    ]
                );
            foreach ($teams as $team) {
                foreach ($team->user->push_subscriptions as $subscription) {
                    $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                            ->title('Formazione non ancora impostatata')
                            ->body('Imposta subito la tua formazione per la giornata ' . $this->currentMatchday->number . '! Ti restano pochi minuti')
                            ->action('Imposta', 'open')
                            ->tag('missing-lineup-' . $this->currentMatchday->number)
                            ->data(['url' => '/teams/' . $team->id . '/lineup']);
                    $io->out('Send push notification to ' . $subscription->endpoint);
                    $webPush->sendNotification($subscription->getSubscription(), json_encode($message));
                }
                $io->out('Create activity in notification stream for team ' . $team->id);
                $feed = $client->feed("notification", $team->id);
                $feed->addActivity([
                    'actor' => 'Team:' . $team->id,
                    'verb' => 'missing',
                    'object' => 'Lineup:'
                ]);
            }
        }
    }
}
