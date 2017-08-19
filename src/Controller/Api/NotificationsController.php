<?php
namespace App\Controller\Api;

use Cake\ORM\TableRegistry;

/**
 *
 */
class NotificationsController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['index']);
    }
    
	public function index()
    {
        $notifications = [];
        $lineups = TableRegistry::get('Lineups');
        $lineup = $lineups->findByTeamIdAndMatchdayId($this->request->getParam('team_id'), $this->currentMatchday->id);
        if ($lineup->isEmpty()) {
            $notifications[] = [
                'title' => 'Non hai ancora impostato la formazione per questa giornata', 
                'url' => 'lineups',
                'severity' => 1
            ];
        }

        $this->set([
            'success' => true,
            'data' => $notifications,
            '_serialize' => ['success', 'data']
        ]);
    }

}
