<?php
namespace App\Controller\Championships;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 */
class EventsController extends AppController
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
        'limit' => 25
    ];
    
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->Crud->action()->findMethod(['byChampionshipId' => 
            ['championship_id' => $this->request->getParam('championship_id')]
        ]);
        return $this->Crud->execute();
    }
}
