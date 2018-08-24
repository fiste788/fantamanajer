<?php
namespace App\Controller\Teams;

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
        $teamId = $this->request->getParam('team_id');
        if (!$this->Authentication->getIdentity()->hasTeam($teamId)) {
            throw new ForbiddenException();
        }
        
        $manager = new ActivityManager();
        $stream = $manager->getActivities('team', $teamId, false);
        $this->set([
            'success' => true,
            'data' => $stream,
            '_serialize' => ['success','data']
        ]);
    }
}
