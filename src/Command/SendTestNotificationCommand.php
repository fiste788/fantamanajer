<?php
declare(strict_types=1);

namespace App\Command;

use App\Traits\CurrentMatchdayTrait;
use App\Utility\WebPush\WebPushMessage;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Log\Log;
use Minishlink\WebPush\WebPush;

/**
 * @property \App\Model\Table\TeamsTable $Teams
 */
class SendTestNotificationCommand extends Command
{
    use CurrentMatchdayTrait;
    use ServiceAwareTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('Credential');
        $this->loadModel('Teams');
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
        $io->out('Parto');
        $webPush = new WebPush(Configure::read('WebPush'));
        $team = $this->Teams->get(55, ['contain' => ['Users.PushSubscriptions']]);
        $io->out('cerco squadra 55');

        foreach ($team->user->push_subscriptions as $subscription) {
            $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                ->title('Notifica di test')
                ->body('Testo molto lungo che ora non sto a scrivere perchè non ho tempo')
                ->image('https://api.fantamanajer.it/files/teams/55/photo/600w/kebab.jpg')
                ->action('Apri', 'open')
                ->tag('missing-lineup-' . $this->currentMatchday->number)
                ->data(['url' => '/teams/' . $team->id . '/lineup']);
            $messageString = json_encode($message);
            if ($messageString != false) {
                $io->out($messageString);
                $io->out('Send push notification to ' . $subscription->endpoint);
                $webPush->sendNotification($subscription->getSubscription(), $messageString);
            }
        }

        $res = $webPush->flush();
        /** \Minishlink\WebPush\MessageSentReport */
        foreach ($res as $result) {
            // you now have access to request & response objects

            /** @var \Psr\Http\Message\ResponseInterface $response */
            $response = $result->getResponse();

            if ($result->isSuccess()) {
                // process successful message sent
                Log::info(sprintf(
                    'Notification with payload %s successfully sent for endpoint %s.',
                    json_decode((string)$response->getBody()),
                    $result->getEndpoint()
                ));
            } else {
                // or a failed one - check expiration first
                if ($result->isSubscriptionExpired()) {
                    // this is just an example code, not included in library!
                    Log::info(sprintf('Expired %s', $result->getEndpoint()));
                    //$db->markExpired($result->getEndpoint());
                } else {
                    // process faulty message
                    Log::info(sprintf(
                        'Notification failed: %s. Payload: %s, endpoint: %s',
                        $result->getReason(),
                        json_decode((string)$response->getBody()),
                        $result->getEndpoint()
                    ));
                }
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
