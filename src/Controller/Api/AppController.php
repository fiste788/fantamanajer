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
    
    public function initialize() {
        parent::initialize();
        
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Index',
                'Crud.View',
                'Crud.Add',
                'Crud.Edit',
                'Crud.Delete'
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.ApiQueryLog'
            ]
        ]);
        $this->Crud->addListener('relatedModels', 'Crud.RelatedModels');
        //$this->RequestHandler->accepts(['xml','json','html']);
        
        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email'],
                    'finder' => 'auth'
                ],
                'ADmad/JwtAuth.Jwt' => [
                    'parameter' => 'token',
                    'userModel' => 'Users',
                    'scope' => ['Users.active' => 1],
                    'fields' => [
                        'username' => 'id'
                    ],
                    'queryDatasource' => true
                ]
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize'
        ]);
        $this->getCurrentMatchday();
    }
    
    public function beforeRender(Event $event) {
        /*$this->response->cors($this->request)
                ->allowOrigin(['develop.fantamanajer.it'])
                ->allowCredentials()
                ->allowMethods(['POST', 'GET', 'PUT', 'DELETE', 'OPTIONS'])
                ->allowHeaders(['origin', 'x-requested-with', 'content-type', 'authorization', 'Access-Control-Allow-Headers'])
                ->build();*/
        //$this->response->withType('application/json');
        $this->RequestHandler->renderAs($this, 'json');
        parent::beforeRender($event);
    }
}