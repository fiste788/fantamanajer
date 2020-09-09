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
use Cake\Log\Log;
use Minishlink\WebPush\WebPush;

/**
 * @property \App\Model\Table\TeamsTable $Teams
 * @property \App\Model\Table\PushSubscriptionsTable $PushSubscriptions
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
        $this->loadService('Credential');
        $this->loadModel('Teams');
        $this->loadModel('PushSubscriptions');
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
        $webPush = new WebPush((array)Configure::read('WebPush'));
        $team = $this->Teams->get(62, ['contain' => ['Users.PushSubscriptions']]);
        $io->out('cerco squadra 55');

        foreach ($team->user->push_subscriptions as $subscription) {
            $message = WebPushMessage::create((array)Configure::read('WebPushMessage.default'))
                ->title('Notifica di test')
                ->body('Testo molto lungo che ora non sto a scrivere perchè non ho tempo')
                ->image('https://api.fantamanajer.it/files/teams/55/photo/600w/kebab.jpg')
                ->action('Apri', 'open')
                ->tag('missing-lineup-' . $this->currentMatchday->number)
                ->data(['url' => '/teams/' . $team->id . '/lineup/current']);
            $messageString = json_encode($message);
            if ($messageString != false) {
                $io->out($messageString);
                $io->out('Send push notification to ' . $subscription->endpoint);
                $webPush->queueNotification($subscription->getSubscription(), $messageString);
            }
        }

        $expired = [];
        $res = $webPush->flush();
        foreach ($res as $result) {
            /** @var \Psr\Http\Message\ResponseInterface $response */
            $response = $result->getResponse();

            if ($result->isSuccess()) {
                // process successful message sent
                Log::info(sprintf(
                    'Notification with payload %s successfully sent for endpoint %s.',
                    $response->getBody()->__toString(),
                    $result->getEndpoint()
                ));
            } else {
                // or a failed one - check expiration first
                if ($result->isSubscriptionExpired()) {
                    // this is just an example code, not included in library!
                    Log::info(sprintf('Expired %s', $result->getEndpoint()));
                    $expired[] = $result->getEndpoint();
                    //$db->markExpired($result->getEndpoint());
                } else {
                    // process faulty message
                    Log::info(sprintf(
                        'Notification failed: %s. Payload: %s, endpoint: %s',
                        $result->getReason(),
                        $response->getBody()->__toString(),
                        $result->getEndpoint()
                    ));
                }
            }
        }
        //$this->PushSubscriptions->updateAll(['expired' => true], ['id' => $expired]);
        $this->PushSubscriptions->deleteAll(['endpoint IN' => $expired]);

        return CommandInterface::CODE_SUCCESS;
    }
}
