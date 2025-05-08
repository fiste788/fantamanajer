<?php
declare(strict_types=1);

namespace App\Controller;

use App\Event\GetStreamEventListener;
use App\Traits\CurrentMatchdayTrait;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\View\JsonView;
use Cake\View\NegotiationRequiredView;
use Crud\Controller\ControllerTrait;
use League\Container\ContainerAwareTrait;
use Override;

/**
 * @property \Crud\Controller\Component\CrudComponent $Crud Description
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization Description
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication Description
 */
class AppController extends Controller
{
    use ControllerTrait;
    use CurrentMatchdayTrait;
    use LocatorAwareTrait;
    use ServiceAwareTrait;
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();

        $this->viewBuilder()->setClassName('Json');

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
     * beforeRender callback.
     *
     * @param \Cake\Event\EventInterface<\Cake\Controller\Controller> $event Event.
     * @return void
     */
    #[Override]
    public function beforeRender(EventInterface $event)
    {
        $this->response = $this->response->withType('application/json');
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    public function afterFind(EventInterface $event): void
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
    public function afterPaginate(EventInterface $event): void
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
    #[Override]
    public function viewClasses(): array
    {
        return [JsonView::class, NegotiationRequiredView::class];
    }

    /**
     * Caching the response based on matchday date
     *
     * @return void
     * @throws \RuntimeException
     */
    public function withMatchdayCache(): void
    {
        $matchdays = $this->getTableLocator()->get('Matchdays');

        /** @var \App\Model\Entity\Matchday $previous */
        $previous = $matchdays->find('previous')->first();
        $this->response = $this->response
            ->withCache($previous->date->toDateTimeString(), $this->currentMatchday->date->timestamp);
    }

    /**
     * Caching the response forever
     *
     * @return void
     * @throws \RuntimeException
     */
    public function withReadonlyCache(DateTime $date): void
    {
        $this->response = $this->response
            ->withCache($date->toDateTimeString(), '+1 year');
    }
}
