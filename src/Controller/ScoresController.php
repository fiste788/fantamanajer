<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Lineup;
use Cake\Event\Event;

/**
 *
 * @property \App\Model\Table\ScoresTable $Scores
 */
class ScoresController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
    }
    
    public function view($id)
    {
        $members = $this->request->getQuery('members');
        $this->Crud->on('afterFind', function (Event $event) use($members) {
            $result = $this->Scores->loadDetails($event->getSubject()->entity, $members);
            if($members) {
                $result->lineup->modules = Lineup::$module;
            }
            return $result;
        });
        $this->Crud->execute();
    }
    
    public function edit()
    {
        $this->Crud->action()->saveOptions(['associated' => ['Lineups.Dispositions']]);
        
        $this->Crud->on('beforeSave', function(\Cake\Event\Event $event) {
            \Cake\Log\Log::debug($event->getSubject()->entity);
    });

        return $this->Crud->execute();
    }
}
