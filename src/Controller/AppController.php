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

        $this->loadComponent('Authentication.Authentication', [
            'logoutRedirect' => '/users/login'  // Default is false
        ]);

        $this->loadComponent('Authorization.Authorization', [
            'skipAuthorization' => [
                'login',
                'home',
                'Clubs.view',
                'Clubs.index',
                'Members.best',
                'Matchdays.current'
            ]
        ]);

        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Index',
                'Crud.View'
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.ApiQueryLog'
            ]
        ]);
        $this->Crud->addListener('relatedModels', 'Crud.RelatedModels');

        $this->getCurrentMatchday();
    }

    public function implementedEvents()
    {
        return parent::implementedEvents() + [
            'Crud.afterFind' => '_afterFind',
            'Crud.afterPaginate' => '_afterPaginate'
        ];
    }

    public function _afterFind(\Cake\Event\Event $event)
    {
        if ($this->Authentication->getIdentity()) {
            $this->Authorization->authorize($event->getSubject()->entity);
        }
    }

    public function _afterPaginate(\Cake\Event\Event $event)
    {
        if ($this->Authentication->getIdentity()) {
            foreach ($event->getSubject()->entities as $entity) {
                $this->Authorization->authorize($entity);
            }
        }
    }

    public function beforeRender(Event $event)
    {
        $this->RequestHandler->renderAs($this, 'json');
        $this->getResponse()->withType('application/json');
        parent::beforeRender($event);
    }
}
