<?php

namespace App\Controller\Championships;

/**
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends \App\Controller\ArticlesController
{
    public $paginate = [
         'limit' => 8
    ];

    public function index()
    {
        $this->Crud->action()->findMethod(['byChampionshipId' => [
            'championship_id' => $this->request->getParam('championship_id')
        ]]);

        return $this->Crud->execute();
    }
}
