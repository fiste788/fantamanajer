<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\MembersTable $Members
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class MembersController extends AppController
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->Matchdays = $this->fetchTable('Matchdays');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['best']);
    }

    public $paginate = [
        'limit' => 50,
    ];

    /**
     * Best
     *
     * @return void
     * @throws \RuntimeException
     */
    public function best()
    {
        // $this->withMatchdayCache();

        $roles = $this->Members->Roles->find()->cache('roles')->toArray();

        /** @var \App\Model\Entity\Matchday|null $matchday */
        $matchday = $this->Matchdays->findWithRatings($this->currentSeason)->first();
        if ($matchday != null) {
            /** @var \App\Model\Entity\Role $role */
            foreach ($roles as $key => $role) {
                $roles[$key]->best_players = $this->Members->find('bestByMatchdayIdAndRole', [
                    'matchday_id' => $matchday->id,
                    'role' => $role,
                ])->limit(5)->toArray();
            }
        }

        $this->set(
            [
                'success' => true,
                'data' => $roles,
                '_serialize' => ['success', 'data'],
            ]
        );
    }
}
