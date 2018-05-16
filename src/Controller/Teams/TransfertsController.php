<?php
namespace App\Controller\Teams;

use App\Controller\AppController;

/**
 *
 * @property \App\Model\Table\TransfertsTable $Transferts
 */
class TransfertsController extends AppController
{
    public function index()
    {
        $this->Crud->action()->findMethod(['byTeamId' => [
            'team_id' => $this->request->getParam('team_id')
        ]]);

        return $this->Crud->execute();
    }
}
