<?php

namespace App\Controller\Championships;

/**
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends \App\Controller\ArticlesController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $championshipId = $this->request->getParam('championship_id');
        if(!$this->Authentication->getIdentity()->isInChampionship($championshipId)) {
            throw new \Cake\Http\Exception\ForbiddenException();
        }
    }
    
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
