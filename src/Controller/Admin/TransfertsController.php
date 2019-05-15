<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Table\TransfertsTable;
use Cake\Event\EventInterface;

/**
 *
 * @property TransfertsTable $Transferts
 */
class TransfertsController extends AppController
{

    public function add()
    {
        $this->Crud->action()->saveOptions(['associated' => []]);
        $this->Crud->on('afterSave', function (EventInterface $event) {
            if ($event->getSubject()->success) {
                $event->getSubject()->entity->substituteMembers();
            }
        });

        return $this->Crud->execute();
    }

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
    }
}
