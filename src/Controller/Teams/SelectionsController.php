<?php
namespace App\Controller\Teams;

class SelectionsController extends \App\Controller\SelectionsController
{

    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
    }

    public function index()
    {
        $this->Crud->action()->findMethod(['byTeamIdAndMatchdayId' => [
            'team_id' => $this->request->getParam('team_id'),
            'matchday_id' => $this->currentMatchday->id
        ]]);
        return $this->Crud->execute();
    }
}
