<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MembersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MembersTable Test Case
 */
class MembersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MembersTable
     */
    public $Members;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.members',
        'app.players',
        'app.roles',
        'app.clubs',
        'app.seasons',
        'app.dispositions',
        'app.lineups',
        'app.matchdays',
        'app.articles',
        'app.teams',
        'app.ratings',
        'app.scores',
        'app.transferts',
        'app.members_teams'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Members') ? [] : ['className' => 'App\Model\Table\MembersTable'];
        $this->Members = TableRegistry::get('Members', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Members);

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
}
