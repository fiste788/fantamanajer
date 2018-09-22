<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ScoresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ScoresTable Test Case
 */
class ScoresTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ScoresTable
     */
    public $ScoresTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.scores',
        'app.lineups',
        'app.teams',
        'app.matchdays'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Scores') ? [] : ['className' => ScoresTable::class];
        $this->ScoresTable = TableRegistry::getTableLocator()->get('Scores', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ScoresTable);

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
     * Test findMaxMatchday method
     *
     * @return void
     */
    public function testFindMaxMatchday()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findScores method
     *
     * @return void
     */
    public function testFindScores()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findRanking method
     *
     * @return void
     */
    public function testFindRanking()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test compute method
     *
     * @return void
     */
    public function testCompute()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test loadDetails method
     *
     * @return void
     */
    public function testLoadDetails()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
