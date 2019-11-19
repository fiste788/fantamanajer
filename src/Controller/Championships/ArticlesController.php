<?php
declare(strict_types=1);

namespace App\Controller\Championships;

use App\Controller\ArticlesController as AppArticlesController;

/**
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppArticlesController
{
    public $paginate = [
        'limit' => 8,
    ];

    /**
     * Index
     *
     * @return \Cake\Http\Response
     */
    public function index()
    {
        $this->Crud->action()->findMethod(['byChampionshipId' => [
            'championship_id' => (int)$this->request->getParam('championship_id'),
        ]]);

        return $this->Crud->execute();
    }
}
