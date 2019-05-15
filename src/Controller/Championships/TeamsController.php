<?php

namespace App\Controller\Championships;

use Cake\Event\EventInterface;
use App\Controller\AppController;

/**
 *
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $championshipId = $this->request->getParam('championship_id');
        if (!$this->Authentication->getIdentity()->isInChampionship($championshipId)) {
            throw new \Cake\Http\Exception\ForbiddenException();
        }
    }

    public function index()
    {
        $this->Crud->action()->findMethod(['byChampionshipId' => [
            'championship_id' => $this->request->getParam('championship_id')
        ]]);

        return $this->Crud->execute();
    }
}
