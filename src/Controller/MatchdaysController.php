<?php
namespace App\Controller;

use Cake\Event\Event;
use DateTime;

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
        $previous = $this->Matchdays->find('previous')->first()->date;
        $this->response = $this->response->withCache($previous, $this->currentMatchday->date->timestamp)->withHeader('Access-Control-Allow-Origin','*');
        // $this->getResponse()->withExpires($this->currentMatchday->date);
        // $this->getResponse()->withModified($this->Matchdays->find('previous')->first()->date);

        $this->set([
            'data' => $this->currentMatchday,
            'success' => true,
            '_serialize' => ['data', 'success']
        ]);
    }
}
