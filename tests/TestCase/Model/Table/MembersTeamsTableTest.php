<?php
declare(strict_types=1);

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
    protected $MembersTeams;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.MembersTeams',
        'app.Teams',
        'app.Members',
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
        $this->MembersTeams = TableRegistry::getTableLocator()->get('MembersTeams', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->MembersTeams);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
