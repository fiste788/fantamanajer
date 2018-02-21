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
     * @param int $id the article id
     * @return type
     */
    public function edit($id)
    {
        $this->Flash->success('The article has been deleted.');

        return $this->Crud->execute();
    }
}
