<?php
declare(strict_types=1);

namespace App\Controller\Championships;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 *
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoresController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $championshipId = $this->request->getParam('championship_id');
        if (!$this->Authentication->getIdentity()->isInChampionship($championshipId)) {
            throw new \Cake\Http\Exception\ForbiddenException();
        }
    }

    public function index()
    {
        $this->Crud->action()->findMethod([
            'ranking' => [
                'championship_id' => $this->request->getParam('championship_id'),
                'scores' => true,
            ],
        ]);

        return $this->Crud->execute();
    }
}
