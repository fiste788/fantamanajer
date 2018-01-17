<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Event\Event;

/**
 *
 * @property \App\Model\Table\SelectionsTable $Selections
 */
class SelectionsController extends AppController
{

    public function index()
    {
        $selections = $this->Selections->findByTeamIdAndMatchdayId($this->request->getParam('team_id'), $this->currentMatchday->id)
            ->contain(['Teams', 'OldMembers.Players', 'NewMembers.Players', 'Matchdays']);
        $this->set(
            [
            'success' => true,
            'data' => $selections->last(),
            '_serialize' => ['success', 'data']
            ]
        );
        //$this->log($articles, \Psr\Log\LogLevel::NOTICE);
    }

    public function add()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                $event->getSubject()->entity->matchday_id = $this->currentMatchday->id;
                $event->getSubject()->entity->active = true;
            }
        );

        $this->Crud->execute();
    }
}
