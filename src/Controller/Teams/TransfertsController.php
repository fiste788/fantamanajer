<?php
declare(strict_types=1);

namespace App\Controller\Teams;

use App\Controller\AppController;

/**
 *
 * @property \App\Model\Table\TransfertsTable $Transferts
 */
class TransfertsController extends AppController
{
    /**
     * Index
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function index()
    {
        /** @var \Crud\Action\IndexAction $action */
        $action = $this->Crud->action();
        $action->findMethod(['byTeamId' => [
            'team_id' => (int)$this->request->getParam('team_id'),
        ]]);

        return $this->Crud->execute();
    }
}
