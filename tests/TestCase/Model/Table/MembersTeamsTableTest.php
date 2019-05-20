<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MembersTeamsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MembersTeamsTable Test Case
 */
class MembersTeamsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MembersTeamsTable
     */
    public $MembersTeamsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MembersTeams',
        'app.Teams',
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
        $config = TableRegistry::getTableLocator()->exists('MembersTeams') ? [] : ['className' => MembersTeamsTable::class];
        $this->MembersTeamsTable = TableRegistry::getTableLocator()->get('MembersTeams', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->MembersTeamsTable);

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
     * Test beforeSave method
     *
     * @return void
     */
    public function testBeforeSave()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
