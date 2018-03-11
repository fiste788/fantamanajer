<?php
namespace App\Controller\Championships;

use App\Controller\AppController;

/**
 * @property \App\Model\Table\MembersTable $Members
 */
class MembersController extends AppController
{
    public $paginate = [
        'limit' => 50,
    ];

    public function free()
    {
        $stats = $this->request->getQuery('stats', true);
        $role = $this->request->getParam('role_id', null);
        $championshipId = $this->request->getParam('championship_id');
        $members = $this->Members->findFree($championshipId);
        if ($stats) {
            $members->contain(['VwMembersStats']);
        }
        if ($role) {
            $members->where(['role_id' => $role]);
        }

        $this->set(
            [
            'success' => true,
            'data' => $members,
            '_serialize' => ['success', 'data']
            ]
        );
    }
}
