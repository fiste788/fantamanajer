<?php
namespace App\Controller\Championships;

use App\Controller\AppController;
use App\Stream\ActivityManager;
use Cake\Http\Exception\ForbiddenException;

/**
 * Steam Controller
 */
class StreamController extends AppController
{
    public function index()
    {
        $championshipId = $this->request->getParam('championship_id');
        if (!$this->Authentication->getIdentity()->isInChampionship($championshipId)) {
            throw new ForbiddenException();
        }

        $manager = new ActivityManager();
        $stream = $manager->getActivities('championship', $championshipId, true);
        $this->set([
            'stream' => $stream,
            '_serialize' => 'stream'
        ]);
    }
}
