<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\View\Helper\UrlHelper;

/**
 *
 * @property \App\Model\Table\TeamsTable $Teams
 * @property UrlHelper $Url
 */
class TeamsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('upload', 'Crud.Edit');
    }

    public function view($id)
    {
        $this->Crud->on(
            'beforeFind',
            function (Event $event) {
                $event->getSubject()->query->contain(['Users', 'PushNotificationSubscriptions', 'EmailNotificationSubscriptions']);
            }
        );

        return $this->Crud->execute();
    }

    public function isAuthorized($user = null)
    {
        if (in_array($this->request->getParam('action'), ['edit'])) {
            foreach ($user['teams'] as $team) {
                if ($team['id'] == $this->request->getParam('id')) {
                    return true;
                }
            }
        } else {
            return true;
        }

        return parent::isAuthorized($user);
    }
}
