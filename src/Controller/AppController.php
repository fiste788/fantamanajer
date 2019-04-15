<?php
//declare (strict_types = 1);

namespace App\Controller;

use App\Event\GetStreamEventListener;
use App\Traits\CurrentMatchdayTrait;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Crud\Controller\ControllerTrait;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Event\EventInterface;

/**
 *
 * @property \Crud\Controller\Component\CrudComponent $Crud Description
 * @property \Cake\Controller\Component\RequestHandlerComponent $RequestHandler
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization Description
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication Description
 */
class AppController extends Controller
{
    use ControllerTrait;
    use CurrentMatchdayTrait;
    use ServiceAwareTrait;

    /**
     * @return void
     */
    public function initialize(): void
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
                //Configure::read('debug') ?? 'Crud.ApiQueryLog'
            ]
        ]);
        $this->Crud->addListener('relatedModels', 'Crud.RelatedModels');
        EventManager::instance()->on(new GetStreamEventListener());

        $this->getCurrentMatchday();
    }

    public function implementedEvents(): array
    {
        return parent::implementedEvents() + [
            'Crud.afterFind' => '_afterFind',
            'Crud.afterPaginate' => '_afterPaginate'
        ];
    }

    public function _afterFind(EventInterface $event)
    {
        if ($this->Authentication->getIdentity()) {
            $this->Authorization->authorize($event->getSubject()->entity);
        }
    }

    public function _afterPaginate(EventInterface $event)
    {
        if ($this->Authentication->getIdentity()) {
            foreach ($event->getSubject()->entities as $entity) {
                $this->Authorization->authorize($entity);
            }
        }
    }

    public function beforeRender(EventInterface $event)
    {
        $this->RequestHandler->renderAs($this, 'json');
        $this->getResponse()->withType('application/json');
        parent::beforeRender($event);
    }
}
