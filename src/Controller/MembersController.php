<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ModelAwareTrait;

/**
 * @property \App\Model\Table\MembersTable $Members
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class MembersController extends AppController
{
    use ModelAwareTrait;

    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['best']);
        $this->loadModel('Matchdays');
    }

    public $paginate = [
        'limit' => 50,
    ];

    public function best()
    {
        $roles = $this->Members->Roles->find()->cache('roles')->toArray();
        $matchday = $this->Matchdays->findWithRatings($this->currentSeason)->first();
        if ($matchday != null) {
            foreach ($roles as $key => $role) {
                $roles[$key]->best_players = $this->Members->find('bestByMatchdayIdAndRole', [
                    'matchday_id' => $matchday->id,
                    'role' => $role
                ])->limit(5)->toArray();
            }
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
