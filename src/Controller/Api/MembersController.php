<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use App\Model\Table\MembersTable;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * @property MembersTable $Members
 */
class MembersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index', 'best']);
    }

    public function index()
    {
        $this->Crud->on(
            'startup',
            function (Event $event) {
                $event->getSubject()->query->contain(['Clubs', 'Seasons', 'Players']);
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
        $defaultRole = $this->request->getParam('role_id', null);
        $championshipId = $this->request->getParam('championship_id');
        $members = $this->Members->findFree($championshipId)->find('withStats', ['season_id' => $this->currentSeason->id]);
        if (!is_null($defaultRole)) {
            $members->where(['role_id' => $defaultRole]);
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
