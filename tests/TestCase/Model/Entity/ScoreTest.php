<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Score;
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
        $matchday = $this->getTableLocator()->get('Matchdays')->get(576);
        $team = $this->getTableLocator()->get('Teams')->get(55, ['contain' => 'Championships']);
        $this->Score->team = $team;
        $this->Score->matchday = $matchday;
        $this->Score->matchday_id = $matchday->id;
        $this->Score->team_id = $team->id;
        $this->Score->compute();
        $this->assertEquals(84, $this->Score->points, 'Points not match');
        $this->assertNull($this->Score->lineup->cloned, null);
    }
    
    /**
     * Test compute method
     *
     * @return void
     */
    public function testMissingLineup()
    {
        $matchday = $this->getTableLocator()->get('Matchdays')->get(577);
        $team = $this->getTableLocator()->get('Teams')->get(55, ['contain' => 'Championships']);
        $this->Score->team = $team;
        $this->Score->matchday = $matchday;
        $this->Score->matchday_id = $matchday->id;
        $this->Score->team_id = $team->id;
        $this->Score->compute();
        $this->assertEquals(57.5, $this->Score->points, 'Points not match');
        $this->assertTrue($this->Score->lineup->cloned);
    }
}
