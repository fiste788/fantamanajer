<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Stream\ActivityManager;
use Cake\Http\Exception\ForbiddenException;

/**
 * Steam Controller
 */
class StreamController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function indexByChampionship()
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
    
    public function indexByTeam()
    {
        $teamId = $this->request->getParam('team_id');
        if (!$this->Authentication->getIdentity()->hasTeam($teamId)) {
            throw new ForbiddenException();
        }
        
        $manager = new ActivityManager();
        $stream = $manager->getActivities('team', $teamId, false);
        $this->set([
            'stream' => $stream,
            '_serialize' => 'stream'
        ]);
    }
}
