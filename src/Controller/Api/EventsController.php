<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use App\Model\Table\EventsTable;
use Cake\ORM\Query;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 */
class EventsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $championship_id = $this->request->getParam('championship_id');
        $events = $this->Events->find()
            ->innerJoinWith(
                'Teams.Championships',
                function (Query $q) use ($championship_id) {
                        return $q->where(['Championships.id' => $championship_id]);
                }
            )
                ->contain('Teams')
                ->orderDesc('created_at')
                ->all();

        $this->set(
            [
            'success' => true,
            'data' => $events,
            '_serialize' => ['success', 'data']
            ]
        );
    }
}
