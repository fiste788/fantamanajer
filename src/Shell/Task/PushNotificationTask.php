<?php

namespace App\Shell\Task;

use App\Traits\CurrentMatchdayTrait;
use App\Utility\WebPush\WebPushMessage;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Minishlink\WebPush\WebPush;

/**
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\LineupsTable $Lineups
 * @property \App\Model\Table\TeamsTable $Teams
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

    public function startup()
    {
        parent::startup();
        if ($this->param('no-interaction')) {
            $this->interactive = false;
        }
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('scores');
        $parser->addSubcommand('missing_lineup');
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true,
            'default' => false
        ]);

        return $parser;
    }

    public function scores()
    {
        $webPush = new WebPush(Configure::read('WebPush'));
        $user = $this->Users->get(2, ['contain' => ['PushSubscriptions']]);
        foreach ($user->push_subscriptions as $subscription) {
            $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                    ->title('Punteggio giornata 2 Le formiche sono amiche')
                    ->body('La tua squadra ha totalizzato un punteggio di 90 punti')
                    ->action('Visualizza', 'open')
                    ->tag(926796012340920300)
                    ->data(['url' => '/scores/last']);
            $this->out('Send notification to ' . $subscription->endpoint);
            $webPush->sendNotification($subscription->getSubscription(),json_encode($message));
        }
        //$webPush->flush();
        $this->out(print_r($webPush->flush()));
    }

    public function missingLineup()
    {
        if ($this->currentMatchday->date->isWithinNext('30 minutes')) {
            $webPush = new WebPush(Configure::read('WebPush'));
            $teams = $this->Teams->find()
                ->contain(['Users.PushSubscriptions'])
                ->innerJoinWith('Championships')
                ->where(
                    [
                        'season_id' => $this->currentSeason->id,
                        'team_id NOT IN' => $this->Lineups->find()->where(['matchday_id' => $this->currentMatchday->id])
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
                    $this->out('Send notification to ' . $subscription->endpoint);
                    $webPush->sendNotification($subscription->getSubscription(),json_encode($message));
                }
            }
        }
    }
}
