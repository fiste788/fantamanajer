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
use Cake\I18n\DateTime;
use GetStream\Stream\Client;
use WebPush\Action;
use WebPush\Notification;

/**
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
        $tomorrow = DateTime::now()->addDays(1)->second(0);
        if (
            $args->getOption('force') == true ||
            $this->currentMatchday->date->isWithinNext('30 minutes') ||
            $this->currentMatchday->date->equals($tomorrow)
        ) {
            $io->out('Start');

            /** @var array<string> $config */
            $config = Configure::read('GetStream.default');
            $client = new Client($config['appKey'], $config['appSecret']);

            $lineupsTable = $this->fetchTable('Lineups');
            $teamsTable = $this->fetchTable('Teams');
            /** @var array<\App\Model\Entity\Team> $teams */
            $teams = $teamsTable->find()
                ->contain(['Users.PushSubscriptions', 'Championships'])
                ->innerJoinWith('Championships')
                ->where(
                    [
                        'season_id' => $this->currentSeason->id,
                        'started' => true,
                        'Teams.id NOT IN' => $lineupsTable->find()->select('team_id')->where([
                            'matchday_id' => $this->currentMatchday->id,
                        ]),
                    ]
                )->all();
            $date = new DateTime($this->currentMatchday->date->getTimestamp());
            foreach ($teams as $team) {
                $action = [
                    'operation' => 'navigateLastFocusedOrOpen',
                    'url' => "/teams/{$team->id}/lineup/current",
                ];
                $body = sprintf(
                    'Ricordati di impostare la formazione per la giornata %d! Ti restano %s',
                    $this->currentMatchday->number,
                    $date->timeAgoInWords()
                );
                $message = $this->PushNotification->createDefaultMessage('Formazione non ancora impostatata', $body)
                    ->addAction(Action::create('open', 'Imposta'))
                    ->withTag('missing-lineup-' . $this->currentMatchday->number)
                    ->withData([
                        'onActionClick' => [
                            'default' => $action,
                            'open' => $action,
                        ],
                    ]);
                $notification = Notification::create()
                    ->withTTL(3600)
                    ->withTopic('missing-lineup')
                    ->withPayload($message->toString());
                $io->out($message->toString());
                foreach ($team->user->push_subscriptions as $subscription) {
                    $io->out("Send push notification to {$subscription->endpoint}");
                    $this->PushNotification->sendAndRemoveExpired($notification, $subscription);
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
