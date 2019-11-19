<?php
declare(strict_types=1);

namespace App\Controller\Teams;

/**
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends \App\Controller\ArticlesController
{
    public $paginate = [
        'limit' => 25,
    ];

    /**
     * Index
     *
     * @return \Cake\Http\Response
     */
    public function index()
    {
        $this->Crud->action()->findMethod([
            'byTeamId' =>
            ['team_id' => (int)$this->request->getParam('team_id')],
        ]);

        return $this->Crud->execute();
    }
}
