<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\Event;
use Cake\Event\EventInterface;
use Override;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamsController extends AppController
{
    /**
     * {@inheritDoc}
     *
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     */
    #[Override]
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('add', 'Crud.Add');
        $this->Crud->mapAction('upload', 'Crud.Edit');
    }

    /**
     * View
     *
     * @param int $id Id
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function view(int $id): ResponseInterface
    {
        $this->Crud->on('beforeFind', function (Event $event): void {
            /** @var \Cake\ORM\Query\SelectQuery $query */
            $query = $event->getSubject()->query;
            $query->contain([
                'Users',
                'Championships' => ['Leagues', 'Seasons'],
                'PushNotificationSubscriptions',
                'EmailNotificationSubscriptions',
            ]);
        });

        return $this->Crud->execute();
    }

    /**
     * Add
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function add(): ResponseInterface
    {
        /** @var \Crud\Action\AddAction $action */
        $action = $this->Crud->action();
        $action->saveOptions(['accessibleFields' => ['user' => true]]);
        $action->saveMethod('saveWithoutUser');

        return $this->Crud->execute();
    }

    /**
     * Edit
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function edit(): ResponseInterface
    {
        /** @var \Crud\Action\EditAction $action */
        $action = $this->Crud->action();
        $action->setConfig([
            'api' => [
                'success' => [
                    'data' => [
                        'entity' => ['photo_url'],
                    ],
                ],
            ],
        ]);
        $action->saveOptions([
            'accessibleFields' => ['user' => false],
            'associated' => ['Members', 'PushNotificationSubscriptions', 'EmailNotificationSubscriptions'],
        ]);

        return $this->Crud->execute();
    }
}
