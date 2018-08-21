<?php
namespace App\Controller\Teams;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\View\CellTrait;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 */
class EventsController extends AppController
{
    use CellTrait;
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $teamId = $this->request->getParam('team_id');
        if (!$this->Authentication->getIdentity()->hasTeam($teamId)) {
            throw new ForbiddenException();
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
        $cell = $this->cell('Stream', [
            'feedName' => 'team',
            'feedId' => $this->request->getParam('team_id')
        ]);
        
        $this->set([
            'cell' => $cell,
            '_serialize' => false
        ]);
    }
}
