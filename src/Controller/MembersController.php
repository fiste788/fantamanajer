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

    public function best()
    {
        $this->Authorization->skipAuthorization();
        $roles = $this->Members->Roles->find()->cache('roles')->toArray();
        $matchday = TableRegistry::get('Matchdays')->findWithRatings($this->currentSeason)->first();
        foreach ($roles as $key => $role) {
            $roles[$key]->best_players = $this->Members->find('bestByMatchdayIdAndRole', [
                'matchday_id' => $matchday->id, 
                'role' => $role
            ])->limit(5)->toArray();
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
