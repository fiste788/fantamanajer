<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Score;
use App\Service\ComputeScoreService;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Score Test Case
 */
class ScoreTest extends TestCase
{
    public $fixtures = ['app.members', 'app.players', 'app.ratings', 'app.teams', 'app.championships', 'app.lineups', 'app.dispositions', 'app.matchdays', 'app.seasons'];

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Score
     */
    public $Score;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Score = new Score();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Score);

        parent::tearDown();
    }

    /**
     * Test compute method
     *
     * @return void
     */
    public function testCompute()
    {
        $scoreCompute = new ComputeScoreService();
        $matchday = $this->getTableLocator()->get('Matchdays')->get(576);
        $team = $this->getTableLocator()->get('Teams')->get(55, ['contain' => 'Championships']);
        $this->Score->team = $team;
        $this->Score->matchday = $matchday;
        $this->Score->matchday_id = $matchday->id;
        $this->Score->team_id = $team->id;
        $scoreCompute->exec($this->Score);
        $this->assertEquals(84, $this->Score->points, 'Points not match expected 84 got ' . $this->Score->points);
        $this->assertNull($this->Score->lineup->cloned);
    }

    /**
     * Test compute method
     *
     * @return void
     */
    public function testMissingLineup()
    {
        $scoreService = new ComputeScoreService();
        $matchday = $this->getTableLocator()->get('Matchdays')->get(577);
        $team = $this->getTableLocator()->get('Teams')->get(55, ['contain' => 'Championships']);
        $this->Score->team = $team;
        $this->Score->matchday = $matchday;
        $this->Score->matchday_id = $matchday->id;
        $this->Score->team_id = $team->id;
        $scoreService->exec($this->Score);
        $this->assertEquals(57.5, $this->Score->points, 'Points not match');
        $this->assertTrue($this->Score->lineup->cloned);
    }
}
