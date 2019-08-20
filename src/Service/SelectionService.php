<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Selection;
use App\Model\Entity\Transfert;
use App\Utility\WebPush\WebPushMessage;
use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use Cake\Mailer\Email;
use Minishlink\WebPush\WebPush;

/**
 * @property \App\Model\Table\MembersTeamsTable $MembersTeams
 * @property \App\Model\Table\SelectionsTable $Selections
 */
class SelectionService
{
    use ModelAwareTrait;

    public function __construct()
    {
        $this->loadModel('MembersTeams');
        $this->loadModel('Selections');
    }

    /**
     *
     * @param \App\Model\Entity\Selection $selection
     */
    public function notifyLostMember(Selection $selection)
    {
        $selection = $this->Selections->loadInto($selection, ['Teams' => [
            'EmailNotificationSubscriptions',
            'PushNotificationSubscriptions',
            'Users.Subscriptions',
        ], 'NewMembers.Players']);
        if ($selection->team->isEmailSubscripted('lost_member')) {
            $email = new Email();
            $email->setTemplate('lost_member')
                ->setViewVars(
                    [
                        'player' => $selection->new_member->player,
                        'baseUrl' => 'https://fantamanajer.it',
                    ]
                )
                ->setSubject('Un altra squadra ti ha soffiato un giocatore selezionato')
                ->setEmailFormat('html')
                ->setTo($selection->team->user->email)
                ->send();
        }
        if ($selection->team->isPushSubscripted('lost_member')) {
            $webPush = new WebPush(Configure::read('WebPush'));
            foreach ($selection->team->user->subscriptions as $subscription) {
                $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                    ->title('Un altra squadra ti ha soffiato un giocatore selezionato')
                    ->body('Hai perso il giocatore ' . $selection->new_member->player->surname . ' ' . $selection->new_member->player->name)
                    ->tag('lost-player-' . $selection->id);
                $webPush->sendNotification($subscription->getSubscription(), json_encode($message));
            }
        }
    }

    public function toTransfert(Selection $selection)
    {
        $transfert = new Transfert();
        $transfert->team_id = $selection->team_id;
        $transfert->matchday_id = $selection->matchday_id;
        $transfert->old_member_id = $selection->old_member_id;
        $transfert->new_member_id = $selection->new_member_id;

        return $transfert;
    }

    public function save(Selection $entity)
    {
        $memberTeam = $this->MembersTeams->find()
            ->contain(['Members'])
            ->where([
                'team_id' => $entity->team_id,
                'member_id' => $entity->old_member_id,
            ])->first();
        $memberTeam->member_id = $entity->new_member_id;
        $this->MembersTeams->save($memberTeam);
    }
}
