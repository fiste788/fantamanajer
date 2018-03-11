<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * @property \App\Model\Table\ClubsTable $Clubs
 */
class ClubsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index', 'view']);
    }

    public function index()
    {
        $clubs = $this->Clubs->findBySeason($this->currentSeason);
        $this->set(
            [
                'success' => true,
                'data' => $clubs,
                '_serialize' => ['success', 'data']
            ]
        );
    }
}
