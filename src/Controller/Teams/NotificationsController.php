<?php
namespace App\Controller\Teams;

use Cake\ORM\TableRegistry;

class NotificationsController extends \App\Controller\AppController
{
    public function index()
    {
        $notifications = [];
        $lineups = TableRegistry::get('Lineups');
        $lineup = $lineups->findByTeamIdAndMatchdayId($this->request->getParam('team_id'), $this->currentMatchday->id);
        if ($lineup->isEmpty()) {
            $notifications[] = [
                'title' => 'Non hai ancora impostato la formazione per questa giornata',
                'url' => '/teams/' . $this->request->getParam('team_id') . '/lineup/current',
                'severity' => 1
            ];
        }

        $this->set(
            [
            'success' => true,
            'data' => $notifications,
            '_serialize' => ['success', 'data']
            ]
        );
    }
}
