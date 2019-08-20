<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\SelectionsController as AppSelectionsController;
use Cake\Event\EventInterface;

class SelectionsController extends AppSelectionsController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
        $teamId = $this->request->getParam('team_id');
        if (!$this->Authentication->getIdentity()->hasTeam($teamId)) {
            throw new \Cake\Http\Exception\ForbiddenException();
        }
    }

    public function index()
    {
        $this->Crud->action()->findMethod(['byTeamIdAndMatchdayId' => [
            'team_id' => $this->request->getParam('team_id'),
            'matchday_id' => $this->currentMatchday->id,
        ]]);

        return $this->Crud->execute();
    }
}
