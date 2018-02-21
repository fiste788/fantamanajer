<?php
namespace App\Controller\Api\Clubs;

use App\Controller\Api\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppController
{
    public $paginate = [
        'limit' => 50,
    ];
    
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index']);
    }

    public function index()
    {
        $season_id = $this->currentSeason->id;
        $club_id = $this->request->getParam('club_id', null);
        $this->Crud->on(
            'beforePaginate',
            function (Event $event) use ($club_id, $season_id) {
                if($club_id != null) {
                    $event->getSubject()->query
                        ->contain(['Roles', 'Players', 'VwMembersStats'])
                        ->matching('Clubs', function($q) use ($club_id) {
                            return $q->where(['Clubs.id' => $club_id]);
                    })->where(['season_id' => $season_id]);
                }
            }
        );

        return $this->Crud->execute();
    }
}
