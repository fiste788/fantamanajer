<?php
declare(strict_types=1);

namespace App\Controller\Clubs;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Override;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppController
{
    /**
     * Undocumented function
     *
     * @return void
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index']);
    }

    /**
     * Pagination
     *
     * @var array<string, mixed>
     */
    protected array $paginate = [
        'limit' => 50,
    ];

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
            'byClubId' => [
                'club_id' => (int)$this->request->getParam('club_id', null),
                'season_id' => $this->currentSeason->id,
            ],
        ]);

        return $this->Crud->execute();
    }
}
