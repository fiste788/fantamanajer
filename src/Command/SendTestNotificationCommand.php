<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\PushNotificationService;
use App\Traits\CurrentMatchdayTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use WebPush\Action;
use WebPush\Notification;

class SendTestNotificationCommand extends Command
{
    use CurrentMatchdayTrait;

    /**
     * @inheritDoc
     */
    public function __construct(private PushNotificationService $PushNotification)
    {
    }

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
     * @throws \Cake\Core\Exception\CakeException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $io->out('Parto');
        $teamsTable = $this->fetchTable('Teams');
        /** @var \App\Model\Entity\Team $team */
        $team = $teamsTable->get(77, contain: ['Users.PushSubscriptions']);
        $io->out('cerco squadra 77');

        $action = [
            'operation' => 'navigateLastFocusedOrOpen',
            'url' => '/teams/' . $team->id . '/lineup/current',
        ];
        $message = $this->PushNotification->createDefaultMessage(
            'Notifica di test',
            'Testo molto lungo che ora non sto a scrivere perchè non ho tempo'
        )
            ->withImage('https://api.fantamanajer.it/files/teams/55/photo/600w/kebab.jpg')
            ->withTag('missing-lineup-' . $this->currentMatchday->number)
            ->renotify()
            ->interactionRequired()
            ->withTimestamp(time())
            ->withData([
                'onActionClick' => [
                    'default' => $action,
                    'open' => $action,
                ],
            ])
            ->addAction(Action::create('open', 'Apri'));
        $notification = Notification::create()
            ->withTTL(3600)
            ->withTopic('missing-lineup')
            ->withPayload($message->toString());
        $io->out($notification->getPayload() ?? '');

        foreach ($team->user->push_subscriptions as $subscription) {
            $io->out('Send push notification to ' . $subscription->endpoint);
            $this->PushNotification->sendAndRemoveExpired($notification, $subscription);
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
