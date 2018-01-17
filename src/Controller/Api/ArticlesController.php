<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Event\Event;

/**
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
{

    public $paginate = [
        'page' => 1,
        'limit' => 5,
        'maxLimit' => 15,
        'sortWhitelist' => [
            'id', 'title'
        ]
    ];

    /**
     *
     * @param Event $event event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->Crud->mapAction('indexByTeam', 'Crud.Index');
        $this->Crud->mapAction(
            'indexByChampionship',
            [
                'className' => 'Crud.Index',
            ]
        );
    }

    /**
     *
     * @param int $id the article id
     * @return type
     */
    public function edit($id)
    {
        $this->Flash->success('The article has been deleted.');

        return $this->Crud->execute();
    }

    /**
     *
     */
    public function indexByTeam()
    {
        $articles = $this->Articles->findByTeamId($this->request->getParam('team_id'));
        $this->set(
            [
                'success' => true,
                'data' => $articles,
                '_serialize' => ['success', 'data']
            ]
        );
    }

    /**
     *
     */
    public function indexByChampionship()
    {
        $articles = $this->Articles->findByChampionshipId($this->request->getParam('championship_id'))->all();
        $this->set(
            [
                'success' => true,
                'data' => $articles,
                '_serialize' => ['success', 'data']
            ]
        );
    }
}
