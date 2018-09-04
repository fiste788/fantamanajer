<?php

namespace App\Command;

use App\Traits\CurrentMatchdayTrait;
use App\Utility\WebPush\WebPushMessage;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Minishlink\WebPush\WebPush;

/**
 * @property \App\Model\Table\TeamsTable $Teams
 */
class SendTestNotificationCommand extends Command
{
    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
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
                    $io->out(json_encode($message));
                    $io->out('Send push notification to ' . $subscription->endpoint);
                    $webPush->sendNotification($subscription->getSubscription(), json_encode($message));
                    print_r($webPush->flush());
                }
                
            
        
    }
}
