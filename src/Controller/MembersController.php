<?php
namespace App\Controller;

use App\Controller\AppController;
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
        $this->Auth->allow(['best']);
    }

    public function best()
    {
        $rolesTable = TableRegistry::get('Roles');
        $roles = $rolesTable->find()->cache('roles')->toArray();
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
}
