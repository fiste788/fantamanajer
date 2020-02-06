<?php
declare(strict_types=1);

namespace App\Controller\Players;

use App\Controller\AppController;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\EventInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\RatingsTable $Ratings
 */
class RatingsController extends AppController
{
    use ModelAwareTrait;

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index']);
    }

    public $paginate = [
        'limit' => 50,
    ];

    /**
     * Ratings
     *
     * @param string $seasonId Season id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(string $seasonId): ResponseInterface
    {
        /** @var \Crud\Action\ViewAction $action */
        $action = $this->Crud->action();
        $action->findMethod(['byPlayerIdAndSeasonId' => [
            'player_id' => $this->getRequest()->getParam('player_id', null),
            'season_id' => $seasonId,
        ]]);

        return $this->Crud->execute();
    }
}
