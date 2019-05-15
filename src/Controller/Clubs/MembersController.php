<?php
namespace App\Controller\Clubs;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index']);
    }

    public $paginate = [
        'limit' => 50,
    ];

    public function index()
    {
        $this->Crud->action()->findMethod([
            'byClubId' => [
                'club_id' => $this->request->getParam('club_id', null),
                'season_id' => $this->currentSeason->id
            ]
        ]);

        return $this->Crud->execute();
    }
}
