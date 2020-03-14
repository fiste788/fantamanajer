<?php
declare(strict_types=1);

namespace App\Controller;

use App\Event\GetStreamEventListener;
use App\Traits\CurrentMatchdayTrait;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use Crud\Controller\ControllerTrait;

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
     * @inheritDoc
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');

        $this->loadComponent('Authentication.Authentication', [
            'logoutRedirect' => '/users/login',
        ]);

        $this->loadComponent('Authorization.Authorization', [
            'skipAuthorization' => [
                'login',
                'home',
                'Clubs.view',
                'Clubs.index',
                'Members.best',
                'Matchdays.current',
            ],
        ]);

        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Index',
                'Crud.View',
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                //Configure::read('debug') ?? 'Crud.ApiQueryLog'
            ],
        ]);
        $this->Crud->addListener('relatedModels', 'Crud.RelatedModels');
        EventManager::instance()->on(new GetStreamEventListener());

        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return parent::implementedEvents() + [
            'Crud.afterFind' => 'afterFind',
            'Crud.afterPaginate' => 'afterPaginate',
        ];
    }

    /**
     * After finds
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     * @throws \Authorization\Exception\ForbiddenException
     */
    public function afterFind(EventInterface $event)
    {
        if ($this->Authentication->getIdentity()) {
            $this->Authorization->authorize($event->getSubject()->entity);
        }
    }

    /**
     * After paginate
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function afterPaginate(EventInterface $event)
    {
        if ($this->Authentication->getIdentity()) {
            foreach ($event->getSubject()->entities as $entity) {
                $this->Authorization->authorize($entity);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function beforeRender(EventInterface $event)
    {
        $this->RequestHandler->renderAs($this, 'json');
        $this->response = $this->response->withType('application/json');
        /*
        $headers = [
            'Origin',
            'X-Requested-With',
            'Content-Type',
            'Authorization',
            'Access-Control-Allow-Headers',
            'X-Http-Method-Override'
        ];
        $this->response = $this->response->cors($this->request)
            ->allowOrigin(['localhost:4200', '*.fantamanajer.it'])
            ->allowMethods(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])
            ->allowHeaders($headers)
            ->allowCredentials()
            ->exposeHeaders($headers)
            ->maxAge(24 * 60 * 60)
            ->build();
*/
        return parent::beforeRender($event);
    }

    /**
     * Caching the response based on matchday date
     *
     * @return void
     */
    public function withMatchdayCache(): void
    {
        $matchdays = TableRegistry::getTableLocator()->get("Matchdays");

        /** @var \App\Model\Entity\Matchday $previous */
        $previous = $matchdays->find('previous')->first();
        $this->response = $this->response
            ->withCache($previous->date->toDateTimeString(), $this->currentMatchday->date->timestamp);
    }
}
