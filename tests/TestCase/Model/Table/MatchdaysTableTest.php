<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MatchdaysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MatchdaysTable Test Case
 */
class MatchdaysTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MatchdaysTable
     */
    public $MatchdaysTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Matchdays',
        'app.Seasons',
        'app.Articles',
        'app.Lineups',
        'app.Ratings',
        'app.Scores',
        'app.Transferts',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Matchdays') ? [] : ['className' => MatchdaysTable::class];
        $this->MatchdaysTable = TableRegistry::getTableLocator()->get('Matchdays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->MatchdaysTable);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findCurrent method
     *
     * @return void
     */
    public function testFindCurrent()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getTargetCountdown method
     *
     * @return void
     */
    public function testGetTargetCountdown()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findWithoutScores method
     *
     * @return void
     */
    public function testFindWithoutScores()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findWithScores method
     *
     * @return void
     */
    public function testFindWithScores()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findWithoutRatings method
     *
     * @return void
     */
    public function testFindWithoutRatings()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findWithRatings method
     *
     * @return void
     */
    public function testFindWithRatings()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
