<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * @property \App\Model\Table\ClubsTable $Clubs
 */
class ClubsController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index','view']);
    }
    
    public function index()
    {
        $this->Crud->action()->findMethod([
            'bySeasonId' => [
                'season_id' => $this->currentSeason->id
            ]
        ]);
        return $this->Crud->execute();
    }
}
