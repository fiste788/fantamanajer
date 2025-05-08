<?php
declare(strict_types=1);

namespace App\Controller\Championships;

use App\Controller\AppController;
use Authorization\Exception\ForbiddenException;
use Cake\Event\EventInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoresController extends AppController
{
    /**
     * {@inheritDoc}
     *
     * @throws \Authorization\Exception\ForbiddenException
     */
    public function beforeFilter(EventInterface $event): void
    {
        $championshipId = (int)$this->request->getParam('championship_id');
        /** @var \App\Model\Entity\User $identity */
        $identity = $this->Authentication->getIdentity();
        if (!$identity->isInChampionship($championshipId)) {
            throw new ForbiddenException();
        }

        parent::beforeFilter($event);
    }

    /**
     * Index
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function index(): ResponseInterface
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod([
            'ranking' => [
                'championship_id' => (int)$this->request->getParam('championship_id'),
                'scores' => true,
            ],
        ]);

        return $this->Crud->execute();
    }
}
