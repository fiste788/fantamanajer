<?php
namespace App\Controller;

use Cake\Event\Event;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class MatchdaysController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('current', 'Crud.View');
        $this->Authentication->allowUnauthenticated(['current']);
        $this->Authorization->skipAuthorization();
    }

    public function current()
    {
        $this->Crud->on('beforeFind', function (Event $event) {
            $event->getSubject()->query = $this->Matchdays->find('current');
        });

        return $this->Crud->execute();
    }
}
