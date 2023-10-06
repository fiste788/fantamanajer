<?php
declare(strict_types=1);

namespace App\Controller\Members;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\RatingsTable $Ratings
 */
class RatingsController extends AppController
{
    public array $paginate = [
        'limit' => 1000,
        'maxLimit' => 1000,
    ];

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index']);
    }

    /**
     * Ratings
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function index(): ResponseInterface
    {
        /** @var \Crud\Action\ViewAction $action */
        $action = $this->Crud->action();
        $action->findMethod(['byMemberId' => [
            'member_id' => $this->getRequest()->getParam('member_id', null),
        ]]);

        return $this->Crud->execute();
    }
}
