<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 *
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoresController extends AppController
{
    public function view($id)
    {
        $this->Crud->on('afterFind', function(\Cake\Event\Event $event) {
            return $this->Scores->loadDetails($event->getSubject()->entity);
        });
        $this->Crud->execute();
    }
}
