<?php
declare(strict_types=1);

namespace App\Controller\Leagues;

use App\Controller\AppController;
use Authorization\Exception\ForbiddenException;
use Cake\Event\EventInterface;
use Override;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \App\Model\Table\ChampionshipsTable $Championships
 */
class ChampionshipsController extends AppController
{
    /**
     * Pagination
     *
     * @var array<string, mixed>
     */
    protected array $paginate = [
        'limit' => 1000,
        'maxLimit' => 1000,
    ];

    /**
     * {@inheritDoc}
     *
     * @throws \Authorization\Exception\ForbiddenException
     */
    #[Override]
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $leagueId = (int)$this->request->getParam('league_id');
        /** @var \App\Model\Entity\User $identity */
        $identity = $this->Authentication->getIdentity();
        if (!$identity->isInLeague($leagueId)) {
            throw new ForbiddenException();
        }
    }

    /**
     * Index
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function index(): ResponseInterface
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod(['byLeagueId' => [
            'league_id' => (int)$this->request->getParam('league_id'),
            'season' => $this->currentSeason,
        ]]);

        return $this->Crud->execute();
    }
}
