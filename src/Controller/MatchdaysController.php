<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 */
class MatchdaysController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('current', 'Crud.View');
        $this->Authentication->allowUnauthenticated(['current']);
        $this->Authorization->skipAuthorization();
    }

    /**
     * Current
     *
     * @return \Cake\Http\Response
     */
    public function current()
    {
        $this->Crud->action()->findMethod(['current']);
        $previous = $this->Matchdays->find('previous')->first()->date;
        $this->response = $this->response->withCache($previous, $this->currentMatchday->date->timestamp)->withHeader('Access-Control-Allow-Origin','*');

        $this->set([
            'data' => $this->currentMatchday,
            'success' => true,
            '_serialize' => ['data', 'success']
        ]);
    }
}
