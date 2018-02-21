<?php
namespace App\Controller\Api\Teams;

use App\Controller\Api\AppController;

/**
 *
 * @property \App\Model\Table\TransfertsTable $Transferts
 */
class TransfertsController extends AppController
{
    public function index()
    {
        $transferts = $this->Transferts->findByTeamId($this->request->getParam('team_id'))
            ->contain(['OldMembers.Players', 'NewMembers.Players', 'Matchdays']);
        $this->set(
            [
            'success' => true,
            'data' => $transferts,
            '_serialize' => ['success', 'data']
            ]
        );
    }
}
