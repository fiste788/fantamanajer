<?php
namespace App\Controller\Championships;

use App\Controller\AppController;

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
        $events = $this->Events->findByChampionshipId($championship_id)
                ->contain(['Teams'])
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
