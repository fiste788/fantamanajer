<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use App\Model\Table\LineupsTable;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 *
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class LineupsController extends AppController
{

    public function current()
    {
        $membersTable = TableRegistry::get('Members');
        $lineup = $this->Lineups->find()
            ->innerJoinWith('Matchdays')
            ->contain(['Dispositions'])
            ->where(
                [
                    'matchday_id <=' => $this->currentMatchday->id,
                    'team_id' => $this->request->getParam('team_id')
                    ]
            )
            ->orderDesc('Matchdays.number')
            ->first();
        if ($lineup->matchday_id != $this->currentMatchday->id) {
            $lineup->id = null;
            $lineup->jolly = null;
            $lineup->created_at = null;
            $lineup->modified_at = null;
        }
        $members = $membersTable->find()
            ->contain(['Roles', 'Players'])
            ->innerJoinWith('Teams')
            ->where(['team_id' => $this->request->getParam('team_id')]);

        $this->set(
            [
            'success' => true,
            'data' => [
                'members' => $members,
                'lineup' => $lineup,
                'modules' => ['1-4-4-2', '1-4-3-3', '1-3-4-3', '1-3-5-2', '1-5-3-2', '1-5-4-1']
            ],
            '_serialize' => ['success', 'data']
            ]
        );
    }

    public function add()
    {
        $this->Crud->on(
            'beforeSave',
            function (Event $event) {
                $event->getSubject()->entity->set('matchday_id', $this->currentMatchday->id);
                //$event->getSubject()->entity->touch();
            }
        );
        $this->Crud->execute();
    }
}
