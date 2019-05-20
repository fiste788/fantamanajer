<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SeasonsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SeasonsTable Test Case
 */
class SeasonsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SeasonsTable
     */
    public $SeasonsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Seasons',
        'app.Championships',
        'app.Matchdays',
        'app.Members'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Seasons') ? [] : ['className' => SeasonsTable::class];
        $this->SeasonsTable = TableRegistry::getTableLocator()->get('Seasons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SeasonsTable);

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
}
