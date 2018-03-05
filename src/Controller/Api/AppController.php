<?php
namespace App\Controller\Api;

use App\Traits\CurrentMatchdayTrait;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Crud\Controller\Component\CrudComponent;
use Crud\Controller\ControllerTrait;

/**
 *
 * @property CrudComponent $Crud Description
 * @property \Cake\Controller\Component\RequestHandlerComponent $RequestHandler
 */
class AppController extends Controller
{

    use ControllerTrait;
    use CurrentMatchdayTrait;

    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent(
            'Crud.Crud',
            [
            'actions' => [
                'Crud.Index',
                'Crud.View',
                
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.ApiQueryLog'
            ]
            ]
        );
        $this->Crud->addListener('relatedModels', 'Crud.RelatedModels');
        //$this->RequestHandler->accepts(['xml','json','html']);

        $this->loadComponent(
            'Auth',
            [
            'storage' => 'Memory',
            'authorize' => 'Controller',
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email'],
                    'finder' => 'auth'
                ],
                'ADmad/JwtAuth.Jwt' => [
                    'parameter' => 'token',
                    'userModel' => 'Users',
                    'finder' => 'auth',
                    'fields' => [
                        'username' => 'id'
                    ],
                    'queryDatasource' => true
                ]
            ],
            'loginAction' => '/users/token',
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize'
            ]
        );
        $this->getCurrentMatchday();
    }
    
    public function isAuthorized($user = null)
    {
        $prefix = $this->request->getParam('prefix');
        // Any registered user can access public functions
        if (!$prefix) {
            return true;
        }
        
        $prefixs = explode("/", strtolower($prefix));
        if (in_array('teams', $prefixs) && in_array($this->request->getParam('action'), ['edit', 'delete', 'add'])) {
                foreach($user['teams'] as $team) {
                    if($team['id'] == $this->request->getParam('team_id')) {
                        return true;
                    }
                }
        } else {
            return true;
        }

        // Default deny
        return false;
    }

    /**
     *
     * @param Event $event event
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $this->RequestHandler->renderAs($this, 'json');
        parent::beforeRender($event);
    }
}
