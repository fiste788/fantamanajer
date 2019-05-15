<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\MembersTable $Members
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class MembersController extends AppController
{
    use ModelAwareTrait;

    public function beforeFilter(EventInterface $event): void
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
