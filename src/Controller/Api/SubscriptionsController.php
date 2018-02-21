<?php
namespace App\Controller\Api;

use App\Model\Table\SubscriptionsTable;
use Cake\Event\Event;

/**
 *
 * @property SubscriptionsTable $Subscriptions
 */
class SubscriptionsController extends AppController
{
    public function add()
    {
        $subscription = $this->Subscriptions->findByEndpoint($this->request->getData('endpoint'));
        if ($subscription->isEmpty()) {
            $this->Crud->on(
                'beforeSave',
                function (Event $event) {
                    $event->getSubject()->entity->set('user_id', $this->Auth->user('id'));
                    $event->getSubject()->entity->set('auth_token', $this->request->getData('keys.auth'));
                    $event->getSubject()->entity->set('public_key', $this->request->getData('keys.p256dh'));
                }
            );
            $this->Crud->execute();
        } else {
            $this->set(
                [
                'success' => true,
                'data' => $subscription->first(),
                '_serialize' => ['success', 'data']
                ]
            );
        }
    }

    public function deleteByEndpoint()
    {
        $entity = $this->Subscriptions->findByEndpoint($this->request->getParam('token'));
        $this->Subscriptions->delete($entity);
    }
}
