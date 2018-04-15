<?php

namespace App\Controller\Championships;

/**
 *
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamsController extends \App\Controller\AppController
{

    public function index()
    {
        $this->Crud->action()->findMethod(['byChampionshipId' => [
            'championship_id' => $this->request->getParam('championship_id')
        ]]);
        return $this->Crud->execute();
    }
}
