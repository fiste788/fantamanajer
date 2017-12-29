<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use App\Model\Table\ArticlesTable;
use Cake\Event\Event;

/**
 *
 * @property ArticlesTable $Articles
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

    public function edit($id)
    {
        $this->Flash->success('The article has been deleted.');

        return $this->Crud->execute();
    }

    public function indexByTeam()
    {
        /* $this->Crud->on('startup', function(\Cake\Event\Event $event) {
          $event->getSubject()->query
          ->contain(['Clubs','Seasons','Players'])
          ->where(['']);
          }); */
        /*
          $this->Crud->action()->findMethod('byTeamId',$this->request->getParam('team_id'));
          return $this->Crud->execute(); */
        $articles = $this->Articles->findByTeamId($this->request->getParam('team_id'));
        $this->set(
            [
            'success' => true,
            'data' => $articles,
            '_serialize' => ['success', 'data']
            ]
        );
        //$this->log($articles, \Psr\Log\LogLevel::NOTICE);
    }

    public function indexByChampionship()
    {
        /* $articles = $this->Articles->findByChampionshipId($this->request->getParam('championship_id'));
          $this->set('success',true);
          $this->set('data',$articles);
          $this->set('_serialize',['success','data']);
          $this->log($articles, \Psr\Log\LogLevel::NOTICE);
          /*$this->Crud->on('startup', function(\Cake\Event\Event $event) {
          $event->getSubject()->query
          ->contain(['Clubs','Seasons','Players'])
          ->where(['']);
          }); */
        $articles = $this->Articles->findByChampionshipId($this->request->getParam('championship_id'));
        $this->set(
            [
            'success' => true,
            'data' => $articles,
            '_serialize' => ['success', 'data']
            ]
        );
        /*
          $this->log("champion " . $this->request->getParam('championship_id'), \Psr\Log\LogLevel::NOTICE);
          $this->Crud->action()->findMethod('byChampionship',['championshipId' => $this->request->getParam('championship_id')]);
          return $this->Crud->execute(); */
    }
}
