<?php
declare(strict_types=1);

namespace App\Service;

use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Datasource\ModelAwareTrait;
use Cake\TestSuite\TestCase;

/**
 * @property \App\Service\LineupService $Lineup
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Model\Table\TeamsTable $Teams
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class ComputeScoreServiceTest extends TestCase
{
    use ModelAwareTrait;
    use ServiceAwareTrait;

    public $fixtures = ['app.Members', 'app.Players', 'app.Ratings', 'app.Teams', 'app.Championships', 'app.Lineups', 'app.Dispositions', 'app.Matchdays', 'app.Seasons'];

    /**
     * Undocumented function
     *
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->loadService('Lineup');
        $this->loadModel('Scores');
        $this->loadModel('Teams');
        $this->loadModel('Matchdays');
        $this->loadModel('Seasons');
        $this->loadModel('Lineups');
    }

    /**
     * @param \App\Model\Entity\Team $team The team
     * @param \App\Model\Entity\Matchday $matchday The matchday
     * @return \App\Model\Entity\Score
     * @throws \PDOException
     */
    public function computeScore(): void
    {
        $scoreCompute = new ComputeScoreService();
        $matchday = $this->getTableLocator()->get('Matchdays')->get(576);
        $team = $this->getTableLocator()->get('Teams')->get(1, ['contain' => 'Championships']);
        $this->Score->team = $team;
        $this->Score->matchday = $matchday;
        $this->Score->matchday_id = $matchday->id;
        $this->Score->team_id = $team->id;
        $scoreCompute->exec($this->Score);
        $this->assertEquals(84, $this->Score->points, 'Points not match expected 84 got ' . $this->Score->points);
        $this->assertNull($this->Score->lineup->cloned);
    }
}
