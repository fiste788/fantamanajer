<?php
namespace App\Controller\Championships;

use App\Controller\AppController;
use App\Model\Table\EventsTable;
use Cake\Http\Exception\ForbiddenException;
use Cake\View\CellTrait;
use Cake\Event\EventInterface;

/**
 * Events Controller
 *
 * @property EventsTable $Events
 */
class EventsController extends AppController
{
    use CellTrait;

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $championshipId = $this->request->getParam('championship_id');
        if (!$this->Authentication->getIdentity()->isInChampionship($championshipId)) {
            throw new ForbiddenException();
        }
    }

    public $paginate = [
        'limit' => 25
    ];

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $cell = $this->cell('Stream', [
            'feedName' => 'championship',
            'feedId' => $this->request->getParam('championship_id'),
            'aggregated' => true
        ]);

        $this->set([
            'cell' => $cell,
            '_serialize' => false
        ]);
        $this->render('\\Stream\\json\\index');
    }
}
