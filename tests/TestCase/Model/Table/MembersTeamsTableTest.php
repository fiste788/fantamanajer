<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MembersTeamsTable;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

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
        $validation = $this->MembersTeams->validationDefault(new Validator());
        $this->assertNotNull($validation);
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $rules = $this->MembersTeams->buildRules(new RulesChecker());
        $this->assertNotNull($rules);
    }

    /**
     * Test beforeSave method
     *
     * @return void
     */
    public function testBeforeSave(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test loadService method
     *
     * @return void
     */
    public function testLoadService(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getServiceLocator method
     *
     * @return void
     */
    public function testGetServiceLocator(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test setServiceLocator method
     *
     * @return void
     */
    public function testSetServiceLocator(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
