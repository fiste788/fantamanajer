<?php
declare(strict_types=1);

namespace App\Command;

use App\Traits\CurrentMatchdayTrait;
use App\Utility\WebPush\WebPushMessage;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use WebPush\Notification;

/**
 * @property \App\Model\Table\TeamsTable $Teams
 * @property \App\Model\Table\PushSubscriptionsTable $PushSubscriptions
 * @property \App\Service\PushNotificationService $PushNotification
 */
class SendTestNotificationCommand extends Command
{
    use CurrentMatchdayTrait;
    use ServiceAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Teams');
        $this->loadModel('PushSubscriptions');
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
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $io->out('Parto');
        $team = $this->Teams->get(62, ['contain' => ['Users.PushSubscriptions']]);
        $io->out('cerco squadra 55');

        foreach ($team->user->push_subscriptions as $subscription) {
            $pushSubscription = $subscription->getSubscription();
            if ($pushSubscription != null) {
                $message = WebPushMessage::create((array)Configure::read('WebPushMessage.default'))
                    ->title('Notifica di test')
                    ->body('Testo molto lungo che ora non sto a scrivere perchè non ho tempo')
                    ->image('https://api.fantamanajer.it/files/teams/55/photo/600w/kebab.jpg')
                    ->action('Apri', 'open')
                    ->tag('missing-lineup-' . $this->currentMatchday->number)
                    ->data(['url' => '/teams/' . $team->id . '/lineup/current']);
                $messageString = json_encode($message);
                if ($messageString != false) {
                    $notification = Notification::create()
                        ->withTTL(3600)
                        ->withTopic('score')
                        ->withPayload($messageString);
                    $io->out($messageString);
                    $io->out('Send push notification to ' . $subscription->endpoint);
                    $this->PushNotification->send($notification, $pushSubscription);
                }
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
