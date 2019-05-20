<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LineupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LineupsTable Test Case
 */
class LineupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LineupsTable
     */
    public $LineupsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Lineups',
        'app.Matchdays',
        'app.Teams',
        'app.Dispositions',
        'app.Scores'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Lineups') ? [] : ['className' => LineupsTable::class];
        $this->LineupsTable = TableRegistry::getTableLocator()->get('Lineups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->LineupsTable);

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
     * Test afterSave method
     *
     * @return void
     */
    public function testAfterSave()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test beforeMarshal method
     *
     * @return void
     */
    public function testBeforeMarshal()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findDetails method
     *
     * @return void
     */
    public function testFindDetails()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findLast method
     *
     * @return void
     */
    public function testFindLast()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findByMatchdayIdAndTeamId method
     *
     * @return void
     */
    public function testFindByMatchdayIdAndTeamId()
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
