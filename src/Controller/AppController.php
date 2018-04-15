<?php
namespace App\Controller;

use App\Traits\CurrentMatchdayTrait;
use Authentication\AuthenticationService;
use Cake\Controller\Component\RequestHandlerComponent;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Crud\Controller\Component\CrudComponent;
use Crud\Controller\ControllerTrait;

/**
 *
 * @property CrudComponent $Crud Description
 * @property RequestHandlerComponent $RequestHandler
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization Description
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication Description
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
        
        $this->loadComponent('Authentication.Authentication', [
            'logoutRedirect' => '/users/login'  // Default is false
        ]);

        $this->loadComponent('Authorization.Authorization', [
            'skipAuthorization' => [
                'login',
                'Members.best',
                'Matchdays.current'
            ]
        ]);
        $this->getCurrentMatchday();
    }

    /**
     * Check if the prefix is 'teams' that the current team is owned by current user
     *
     * @param array $user
     * @return boolean
     */
    public function isAuthorized($user = null)
    {
        $prefix = $this->request->getParam('prefix');
        // Any registered user can access public functions
        if (!$prefix) {
            return true;
        }

        $prefixs = explode("/", strtolower($prefix));
        if (in_array('teams', $prefixs) && in_array($this->request->getParam('action'), ['edit', 'delete', 'add'])) {
            foreach ($user['teams'] as $team) {
                if ($team['id'] == $this->request->getParam('team_id')) {
                    return true;
                }
            }
        } else {
            return true;
        }

        return parent::isAuthorized($user);
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
