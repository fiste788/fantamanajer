<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Override;

/**
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppController
{
    /**
     * Undocumented function
     *
     * @return void
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    #[Override]
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['best']);
    }

    /**
     * Pagination
     *
     * @var array<string, mixed>
     */
    protected array $paginate = [
        'limit' => 50,
    ];

    /**
     * Best
     *
     * @return void
     * @throws \RuntimeException
     */
    public function best(int $id): void
    {
        // $this->withMatchdayCache();

        //$roles = $this->Members->Roles->find()->cache('roles')->toArray();

        /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
        $matchdaysTable = $this->fetchTable('Matchdays');
        $matchday = $matchdaysTable->get($id);
        /** @var \App\Model\Entity\Matchday|null $matchdayWithScore */
        $matchdayWithScore = $matchdaysTable->findWithRatings($this->currentSeason)->first();
        if ($matchdayWithScore != null) {
            $targetMatchday = $matchday->number > $matchdayWithScore->number ? $matchdayWithScore : $matchday;
            $query = $this->Members->find(
                'bestByMatchdayId',
                matchday_id: $targetMatchday->id,
            );
            if ($targetMatchday->id == $id) {
                $query = $query->cache('best');
                $this->withReadonlyCache($targetMatchday->date);
            }
            $roles = $query->toArray();
        }

        $this->set(
            [
                'success' => true,
                'data' => $roles ?? [],
            ],
        );
        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }
}
