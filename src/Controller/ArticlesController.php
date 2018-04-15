<?php

namespace App\Controller;

use App\Controller\AppController;

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

    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('delete', 'Crud.Delete');
    }
    
    public function edit($id)
    {
        $this->Crud->on('afterFind', function(\Cake\Event\Event $event) {
            $this->Authorization->authorize($event->getSubject()->entity);
        });

        return $this->Crud->execute();
    }
}
