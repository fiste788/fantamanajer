<?php

namespace App\Shell\Task;

use App\Model\Table\MatchdaysTable;
use App\Model\Table\SeasonsTable;
use App\Model\Table\UsersTable;
use App\Traits\CurrentMatchdayTrait;
use App\Utility\WebPush\WebPushMessage;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Minishlink\WebPush\WebPush;

/**
 * @property SeasonsTable $Seasons
 * @property MatchdaysTable $Matchdays
 * @property UsersTable $Users
 */
class PushNotificationTask extends Shell {

    use CurrentMatchdayTrait;

    public function initialize() {
        parent::initialize();
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
        $this->loadModel('Users');
        $this->getCurrentMatchday();
    }

    public function main() {
        $webPush = new WebPush(Configure::read('WebPush'));
        $user = TableRegistry::get('Users')->get(2, ['contain' => ['Subscriptions']]);
        foreach ($user->subscriptions as $subscription) {
            $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                    ->title('Punteggio giornata 2 Le formiche sono amiche')
                    ->body('La tua squadra ha totalizzato un punteggio di 90 punti')
                    ->action('Visualizza', 'open')
                    ->tag(926796012340920300)
                    ->data(['url' => '/scores/last']);

            $webPush->sendNotification(
                    $subscription->endpoint, json_encode($message), $subscription->public_key, $subscription->auth_token
            );
        }
        $webPush->flush();
        //$this->out(print_r($webPush->flush()));
    }

}
