<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\ClubsTable $Clubs
 */
class ClubsController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index', 'view']);
    }

    /**
     * Index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        $this->Crud->action()->findMethod([
            'bySeasonId' => [
                'season_id' => $this->currentSeason->id,
            ],
        ]);

        return $this->Crud->execute();
    }
}
