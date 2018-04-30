<?php

namespace App\Controller\Teams;

/**
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends \App\Controller\ArticlesController
{

    public $paginate = [
         'limit' => 25
    ];

    public function index()
    {
        $this->Crud->action()->findMethod(['byTeamId' =>
            ['team_id' => $this->request->getParam('team_id')]
        ]);

        return $this->Crud->execute();
    }
}
