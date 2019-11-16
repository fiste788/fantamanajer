<?php
declare(strict_types=1);

namespace App\Controller\Championships;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 *
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamsController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $championshipId = $this->request->getParam('championship_id');
        if (!$this->Authentication->getIdentity()->isInChampionship($championshipId)) {
            throw new \Cake\Http\Exception\ForbiddenException();
        }
    }

    /**
     * Index
     *
     * @return \Cake\Http\Response
     */
    public function index()
    {
        $this->Crud->action()->findMethod(['byChampionshipId' => [
            'championship_id' => $this->request->getParam('championship_id'),
        ]]);

        return $this->Crud->execute();
    }
}
