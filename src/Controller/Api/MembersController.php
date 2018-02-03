<?php
namespace App\Controller\Api;

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
        $this->Auth->allow(['index', 'best']);
    }

    public function index()
    {
        $team_id = $this->request->getParam('team_id', null);
        $this->Crud->on(
            'beforePaginate',
            function (Event $event) use ($team_id) {
                $event->getSubject()->query->contain(['Clubs', 'Players']);
                if($team_id != null) {
                     $event->getSubject()->query->matching('Teams', function($q) use ($team_id) {
                        return $q->where(['Teams.id' => $team_id]);
                    });
                }
            }
        );

        return $this->Crud->execute();
    }

    public function best()
    {
        $rolesTable = TableRegistry::get('Roles');
        $roles = $rolesTable->find()->toArray();
        $matchday = TableRegistry::get('Matchdays')->findWithRatings($this->currentSeason)->first();
        foreach ($roles as $key => $role) {
            $best = $this->Members->findBestByMatchday($matchday, $role)->toArray();
            $roles[$key]->best_players = $best;
        }

        $this->set(
            [
            'success' => true,
            'data' => $roles,
            '_serialize' => ['success', 'data']
            ]
        );
    }

    public function free()
    {
        $stats = $this->request->getQuery('stats', true);
        $role = $this->request->getParam('role_id', null);
        $championshipId = $this->request->getParam('championship_id');
        $members = $this->Members->findFree($championshipId);
        if ($stats) {
            $members->contain(['VwMembersStats']);
        }
        if ($role) {
            $members->where(['role_id' => $role]);
        }

        $this->set(
            [
            'success' => true,
            'data' => $members,
            '_serialize' => ['success', 'data']
            ]
        );
    }
}
