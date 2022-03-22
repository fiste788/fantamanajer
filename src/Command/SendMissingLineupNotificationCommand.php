<?php
declare(strict_types=1);

namespace App\Command;

use App\Traits\CurrentMatchdayTrait;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use GetStream\Stream\Client;
use WebPush\Action;
use WebPush\Notification;

/**
 * @property \App\Model\Table\LineupsTable $Lineups
 * @property \App\Model\Table\TeamsTable $Teams
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Service\PushNotificationService $PushNotification
 */
class SendMissingLineupNotificationCommand extends Command
{
    use CurrentMatchdayTrait;
    use ServiceAwareTrait;

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
        $this->Lineups = $this->fetchTable('Lineups');
        $this->Teams = $this->fetchTable('Teams');
        $this->Matchdays = $this->fetchTable('Matchdays');
        $this->loadService('PushNotification');
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
     * {@inheritDoc}
     *
     * @throws \ErrorException
     * @throws \RuntimeException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $tomorrow = FrozenTime::now()->addDay()->second(0);
        if (
            $args->getOption('force') ||
            $this->currentMatchday->date->isWithinNext('30 minutes') ||
            $this->currentMatchday->date->eq($tomorrow)
        ) {
            $io->out('Start');

            /** @var string[] $config */
            $config = Configure::read('GetStream.default');
            $client = new Client($config['appKey'], $config['appSecret']);

            /** @var \App\Model\Entity\Team[] $teams */
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
                )->all();
            $date = new FrozenTime($this->currentMatchday->date->getTimestamp());
            foreach ($teams as $team) {
                $action = [
                    'operation' => 'navigateLastFocusedOrOpen',
                    'url' => '/teams/' . $team->id . '/lineup/current'
                ];
                $message = $this->PushNotification->createDefaultMessage('Formazione non ancora impostatata', sprintf(
                    'Ricordati di impostare la formazione per la giornata %d! Ti restano %s',
                    $this->currentMatchday->number,
                    $date->timeAgoInWords()
                ))
                    ->addAction(Action::create('open', 'Imposta'))
                    ->withTag('missing-lineup-' . $this->currentMatchday->number)
                    ->withData(['onActionClick' => [
                        'default' => $action,
                        'open' => $action,
                    ]]);
                foreach ($team->user->push_subscriptions as $subscription) {
                    $pushSubscription = $subscription->getSubscription();
                    if ($pushSubscription != null) {
                        $io->out('Send push notification to ' . $subscription->endpoint);
                        $notification = Notification::create()
                            ->withTTL(3600)
                            ->withTopic('missing-lineup')
                            ->withPayload($message->toString());
                        $io->out($message->toString());
                        $io->out('Send push notification to ' . $subscription->endpoint);
                        $this->PushNotification->send($notification, $pushSubscription);
                    }
                }

                $io->out('Create activity in notification stream for team ' . (string)$team->id);
                $feed = $client->feed('notification', (string)$team->id);
                $feed->addActivity([
                    'actor' => 'Team:' . (string)$team->id,
                    'verb' => 'missing',
                    'object' => 'Lineup:',
                ]);
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
