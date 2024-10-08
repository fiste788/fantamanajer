<?php
declare(strict_types=1);

namespace App\Controller\Championships;

use App\Controller\AppController;
use App\Stream\ActivityManager;
use Authorization\Exception\ForbiddenException;
use Cake\Utility\Hash;

/**
 * Steam Controller
 *
 * @property \Cake\ORM\Table $Stream
 */
class StreamController extends AppController
{
    /**
     * Index
     *
     * @return void
     * @throws \Authorization\Exception\ForbiddenException
     * @throws \InvalidArgumentException
     * @throws \GetStream\Stream\StreamFeedException
     */
    public function index(): void
    {
        $championshipId = (int)$this->request->getParam('championship_id');
        /** @var \App\Model\Entity\User $identity */
        $identity = $this->Authentication->getIdentity();
        if (!$identity->isInChampionship($championshipId)) {
            throw new ForbiddenException();
        }

        $page = (int)Hash::get($this->request->getQueryParams(), 'page', 1);
        $rowsForPage = 10;
        $offset = $rowsForPage * ($page - 1);
        $manager = new ActivityManager();
        $stream = $manager->getActivities('championship', (string)$championshipId, true, $offset, $rowsForPage);
        $this->set([
            'success' => true,
            'data' => $stream,
        ]);

        $this->viewBuilder()->setOption('serialize', ['data', 'success']);
    }
}
