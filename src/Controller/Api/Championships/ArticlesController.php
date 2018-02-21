<?php

namespace App\Controller\Api\Championships;

/**
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends \App\Controller\Api\ArticlesController
{
    /**
     *
     */
    public function index()
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
