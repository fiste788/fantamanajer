<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 *
 * @property \App\Model\Table\TransfertsTable $Transferts
 */
class TransfertsController extends AppController
{
    /**
     * Add
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
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

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
    }
}
