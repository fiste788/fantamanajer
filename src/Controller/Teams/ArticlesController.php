<?php

namespace App\Controller\Teams;

/**
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends \App\Controller\ArticlesController
{

    /**
     *
     */
    public function index()
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
}
