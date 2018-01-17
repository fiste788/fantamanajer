<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\Routing\Router;
use Cake\View\Helper\UrlHelper;

/**
 *
 * @property \App\Model\Table\TeamsTable $Teams
 * @property UrlHelper $Url
 */
class TeamsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['upload']);
        $this->Crud->mapAction('upload', 'Crud.Edit');
    }

    public function view($id)
    {
        $this->Crud->on(
            'beforeFind',
            function (Event $event) {
                $event->getSubject()->query->contain(
                    ['Users', 'Members' => function (Query $q) {
                        return $q->contain(['Roles', 'Players', 'Clubs'])
                            ->find('withStats', ['season_id' => $this->currentSeason->id]);
                    }
                    ]
                );
            }
        );

        return $this->Crud->execute();
    }

    public function index()
    {
        $teams = $this->Teams->find()
            ->contain(['Users'])
            ->where(['championship_id' => $this->request->getParam('championship_id')]);
        $this->set(
            [
            'success' => true,
            'data' => $teams,
            '_serialize' => ['success', 'data']
            ]
        );
    }

    public function edit($id)
    {
        if ($this->Teams->find()->where(['user_id' => $this->Auth->user('id'), 'id' => $id])->isEmpty()) {
            return new UnauthorizedException('Access denied');
        }

        return $this->Crud->execute();
    }
}
