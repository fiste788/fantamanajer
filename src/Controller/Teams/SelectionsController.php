<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\SelectionsController as AppSelectionsController;
use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\SelectionsTable $Selections
 */
class SelectionsController extends AppSelectionsController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
        $teamId = (int)$this->request->getParam('team_id');

        /** @var \App\Model\Entity\User $identity */
        $identity = $this->Authentication->getIdentity();
        if (!$identity->hasTeam($teamId)) {
            throw new \Cake\Http\Exception\ForbiddenException();
        }
    }

    /**
     * Index
     *
     * @return \Cake\Http\Response
     */
    public function index()
    {
        $this->Crud->action()->findMethod(['byTeamIdAndMatchdayId' => [
            'team_id' => $this->request->getParam('team_id'),
            'matchday_id' => $this->currentMatchday->id,
        ]]);

        return $this->Crud->execute();
    }
}
