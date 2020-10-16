<?php
declare(strict_types=1);

namespace App\Controller\Users;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\PublicKeyCredentialSourcesTable $PublicKeyCredentialSources
 */
class PublicKeyCredentialSourcesController extends AppController
{
    /**
     * {@inheritDoc}
     *
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('index', 'Crud.Index');
        $this->Crud->mapAction('delete', 'Crud.Delete');
    }

    /**
     * Index
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function index(): ResponseInterface
    {
        /** @var \App\Model\Entity\User $user */
        $user = $this->Authentication->getIdentity();
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod([
            'byUuid' => ['uuid' => $user->uuid],
        ]);

        return $this->Crud->execute();
    }
}
