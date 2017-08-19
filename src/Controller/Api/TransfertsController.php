<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use App\Model\Table\TransfertsTable;

/**
 *
 * @property TransfertsTable $Transferts
 */
class TransfertsController extends AppController
{
    public function index()
    {
        $transferts = $this->Transferts->findByTeamId($this->request->getParam('team_id'))
                ->contain(['OldMembers.Players','NewMembers.Players','Matchdays']);
        $this->set([
            'success' => true,
            'data' => $transferts,
            '_serialize' => ['success','data']
        ]);
    }
}
