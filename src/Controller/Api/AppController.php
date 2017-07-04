<?php
namespace App\Controller\Api;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Crud\Controller\ControllerTrait;

/**
 * 
 * @property \Crud\Controller\Component\CrudComponent $Crud Description
 */
class AppController extends Controller
{
    
    use ControllerTrait;
    
    /**
     *
     * @var Matchday
     */
    protected $currentMatchday;

    /**
     *
     * @var Season
     */
    protected $currentSeason;
    
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
        $matchdays = TableRegistry::get("Matchdays");
        $this->currentMatchday = $matchdays->getCurrent();
        $this->currentSeason = TableRegistry::get("Seasons")->get($this->currentMatchday->get('season_id'));
    }
	
	public function beforeFilter(Event $event) {
        $this->response->cors($this->request)
                ->allowOrigin(['*'])
                ->allowMethods(['POST', 'GET', 'PUT', 'DELETE', 'OPTIONS'])
                ->allowHeaders(['origin', 'x-requested-with', 'content-type'])
                ->build();
        $this->RequestHandler->renderAs($this, 'json');
        parent::beforeFilter($event);
    }
}