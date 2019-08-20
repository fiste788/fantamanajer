<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RatingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RatingsTable Test Case
 */
class RatingsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RatingsTable
     */
    public $RatingsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Ratings',
        'app.Members',
        'app.Matchdays',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Ratings') ? [] : ['className' => RatingsTable::class];
        $this->RatingsTable = TableRegistry::getTableLocator()->get('Ratings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->RatingsTable);

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
     * Test existMatchday method
     *
     * @return void
     */
    public function testExistMatchday()
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
}
