<?php

namespace App\Shell\Task;

use App\Model\Table\LineupsTable;
use App\Model\Table\MatchdaysTable;
use App\Model\Table\SeasonsTable;
use App\Model\Table\TeamsTable;
use App\Model\Table\UsersTable;
use App\Traits\CurrentMatchdayTrait;
use App\Utility\WebPush\WebPushMessage;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Minishlink\WebPush\WebPush;

/**
 * @property SeasonsTable $Seasons
 * @property MatchdaysTable $Matchdays
 * @property UsersTable $Users
 * @property LineupsTable $Lineups
 * @property TeamsTable $Teams
 */
class PushNotificationTask extends Shell
{

    use CurrentMatchdayTrait;

    /**
     *
     * @var WebPush
     */
    protected $webPush;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
        $this->loadModel('Users');
        $this->loadModel('Lineups');
        $this->loadModel('Teams');
        $this->getCurrentMatchday();
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('scores_notification');
        $parser->addSubcommand('missing_lineup_notification');

        return $parser;
    }

    public function scoresNotification()
    {
        $webPush = new WebPush(Configure::read('WebPush'));
        $user = TableRegistry::get('Users')->get(2, ['contain' => ['Subscriptions']]);
        foreach ($user->subscriptions as $subscription) {
            $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                    ->title('Punteggio giornata 2 Le formiche sono amiche')
                    ->body('La tua squadra ha totalizzato un punteggio di 90 punti')
                    ->action('Visualizza', 'open')
                    ->tag(926796012340920300)
                    ->data(['url' => '/scores/last']);
            $this->out('Send notification to ' . $subscription->endpoint);
            $webPush->sendNotification(
                $subscription->endpoint,
                json_encode($message),
                $subscription->public_key,
                $subscription->auth_token
            );
        }
        //$webPush->flush();
        $this->out(print_r($webPush->flush()));
    }

    public function missingLineupNotification()
    {
        if ($this->currentMatchday->date->isWithinNext('30 minutes')) {
            $webPush = new WebPush(Configure::read('WebPush'));
            $teams = $this->Teams->find()
                ->contain(['Users.subscription'])
                ->innerJoinWith('Championships')
                ->where(
                    [
                        'season_id' => $this->currentSeason->id,
                        'team_id NOT IN' => $this->Lineups->find()->where(['matchday_id' => $this->currentMatchday->id])
                        ]
                );
            foreach ($teams as $team) {
                foreach ($team->user->subscriptions as $subscription) {
                    $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                            ->title('Formazione non ancora impostatata')
                            ->body('Imposta subito la tua formazione per la giornata ' . $this->currentMatchday->number . '! Ti restano pochi minuti')
                            ->action('Imposta', 'open')
                            ->tag('missing-lineup-' . $this->currentMatchday->number)
                            ->data(['url' => '/teams/' . $team->id . '/lineup']);
                    $this->out('Send notification to ' . $subscription->endpoint);
                    $webPush->sendNotification(
                        $subscription->endpoint,
                        json_encode($message),
                        $subscription->public_key,
                        $subscription->auth_token
                    );
                }
            }
        }
    }
}
