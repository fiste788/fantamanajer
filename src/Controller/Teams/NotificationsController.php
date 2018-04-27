<?php
namespace App\Controller\Teams;

use Cake\ORM\TableRegistry;

class NotificationsController extends \App\Controller\AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $teamId = $this->request->getParam('team_id');
        if(!$this->Authentication->getIdentity()->hasTeam($teamId)) {
            throw new \Cake\Http\Exception\ForbiddenException();
        }
    }
    
    public function index()
    {
        $teamId = $this->request->getParam('team_id');
        $notifications = [];
        $lineups = TableRegistry::get('Lineups');
        $lineup = $lineups->findByTeamIdAndMatchdayId($teamId, $this->currentMatchday->id);
        if ($lineup->isEmpty()) {
            $notifications[] = [
                'title' => 'Non hai ancora impostato la formazione per questa giornata',
                'url' => '/teams/' . $teamId . '/lineup/current',
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
