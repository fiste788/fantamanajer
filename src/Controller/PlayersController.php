<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * @property \App\Model\Table\PlayersTable $Players
 */
class PlayersController extends AppController
{
    public function view($id)
    {
        $this->Crud->action()->findMethod(['withDetails' => [
            'championship_id' => $this->request->getQuery('championship_id', null)
        ]]);
        return $this->Crud->execute();
    }
}
