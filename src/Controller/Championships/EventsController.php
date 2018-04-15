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
