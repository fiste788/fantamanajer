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
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $championshipId = (int)$this->request->getParam('championship_id');
        /** @var \App\Model\Entity\User $identity */
        $identity = $this->Authentication->getIdentity();
        if (!$identity->isInChampionship($championshipId)) {
            throw new \Cake\Http\Exception\ForbiddenException();
        }

        return parent::beforeFilter($event);
    }

    /**
     * Index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        $this->Crud->action()->findMethod([
            'ranking' => [
                'championship_id' => (int)$this->request->getParam('championship_id'),
                'scores' => true,
            ],
        ]);

        return $this->Crud->execute();
    }
}
