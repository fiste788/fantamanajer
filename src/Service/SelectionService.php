<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Selection;
use App\Model\Entity\Transfert;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Mailer\Mailer;
use Cake\ORM\Locator\LocatorAwareTrait;
use WebPush\Notification;

/**
 * @property \App\Service\PushNotificationService $PushNotification
 */
#[\AllowDynamicProperties]
class SelectionService
{
    use LocatorAwareTrait;
    use ServiceAwareTrait;

    /**
     * Construct
     *
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->loadService('PushNotification');
    }

    /**
     * @param \App\Model\Entity\Selection $selection Selection
     * @return void
     * @throws \ErrorException
     * @throws \Cake\Core\Exception\CakeException
     * @throws \Cake\Mailer\Exception\MissingMailerException
     * @throws \Cake\Mailer\Exception\MissingActionException
     * @throws \BadMethodCallException
     */
    public function notifyLostMember(Selection $selection): void
    {
        /** @var \App\Model\Entity\Selection $selection */
        $selection = $this->fetchTable('Selections')->loadInto($selection, [
            'Teams' => [
                'EmailNotificationSubscriptions',
                'PushNotificationSubscriptions',
                'Users.PushSubscriptions',
            ],
            'NewMembers.Players',
        ]);
        if ($selection->team->isEmailSubscripted('lost_member')) {
            $email = new Mailer();
            $email->setViewVars(
                [
                    'player' => $selection->new_member->player,
                    'baseUrl' => 'https://fantamanajer.it',
                ]
            )
                ->setSubject('Un altra squadra ti ha soffiato un giocatore selezionato')
                ->setEmailFormat('html')
                ->setTo($selection->team->user->email)
                ->viewBuilder()->setTemplate('lost_member');
            $email->deliver();
        }
        if ($selection->team->isPushSubscripted('lost_member')) {
            $message = $this->PushNotification->createDefaultMessage(
                'Un altra squadra ti ha soffiato un giocatore selezionato',
                "Hai perso il giocatore {$selection->new_member->player->full_name}"
            )->withTag('lost-player-' . $selection->id);
            $notification = Notification::create()
                ->withTTL(3600)
                ->withTopic('player-lost')
                ->withPayload($message->toString());
            foreach ($selection->team->user->push_subscriptions as $subscription) {
                $this->PushNotification->sendAndRemoveExpired($notification, $subscription);
            }
        }
    }

    /**
     * Transform selection to transfert
     *
     * @param \App\Model\Entity\Selection $selection Selection
     * @return \App\Model\Entity\Transfert
     */
    public function toTransfert(Selection $selection): Transfert
    {
        $transfert = new Transfert();
        $transfert->team_id = $selection->team_id;
        $transfert->matchday_id = $selection->matchday_id;
        $transfert->old_member_id = $selection->old_member_id;
        $transfert->new_member_id = $selection->new_member_id;

        return $transfert;
    }

    /**
     * Save selection
     *
     * @param \App\Model\Entity\Selection $entity selection
     * @throws \Cake\Core\Exception\CakeException
     * @return void
     */
    public function save(Selection $entity): void
    {
        /** @var \App\Model\Entity\MembersTeam $memberTeam */
        $memberTeam = $this->fetchTable('MembersTeams')->find()
            ->contain(['Members'])
            ->where([
                'team_id' => $entity->team_id,
                'member_id' => $entity->old_member_id,
            ])->first();
        $memberTeam->member_id = $entity->new_member_id;
        $this->fetchTable('MembersTeams')->save($memberTeam);
    }
}
