<?php
declare(strict_types=1);

namespace App\Controller\Clubs;

use App\Controller\AppController;
use App\Stream\ActivityManager;
use Cake\Event\EventInterface;
use Cake\Utility\Hash;

/**
 * Steam Controller
 *
 * @property \Cake\ORM\Table $Stream
 */
class StreamController extends AppController
{
    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index']);
    }

    /**
     * Index
     *
     * @return void
     * @throws \InvalidArgumentException
     * @throws \GetStream\Stream\StreamFeedException
     */
    public function index(): void
    {
        $clubId = (string)$this->request->getParam('club_id');

        $page = (int)Hash::get($this->request->getQueryParams(), 'page', 1);
        $rowsForPage = 10;
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('club', $clubId, false, $offset, $rowsForPage);
        $this->set([
            'stream' => $stream,
        ]);

        $this->viewBuilder()->setOption('serialize', ['stream']);
    }
}
