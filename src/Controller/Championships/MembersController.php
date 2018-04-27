<?php
namespace App\Controller\Championships;

use App\Controller\AppController;

/**
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppController
{   
    public $paginate = [
        'limit' => 200,
    ];
    
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('free', 'Crud.Index');
        $championshipId = $this->request->getParam('championship_id');
        if(!$this->Authentication->getIdentity()->isInChampionship($championshipId)) {
            throw new \Cake\Http\Exception\ForbiddenException();
        }
    }

    public function free()
    {
        $this->Crud->action()->findMethod([
            'free' => [
                'championship_id' => $this->request->getParam('championship_id'),
                'stats' => $this->request->getQuery('stats', true),
                'role' => $this->request->getParam('role_id', null)
            ]
        ]);
        return $this->Crud->execute();
    }
}
