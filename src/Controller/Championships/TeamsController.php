<?php

namespace App\Controller\Championships;

/**
 *
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamsController extends \App\Controller\TeamsController
{

    public function index()
    {
        $teams = $this->Teams->find()
            ->contain(['Users'])
            ->where(['championship_id' => $this->request->getParam('championship_id')]);
        $this->set(
            [
                'success' => true,
                'data' => $teams,
                '_serialize' => ['success', 'data']
            ]
        );
    }
}
