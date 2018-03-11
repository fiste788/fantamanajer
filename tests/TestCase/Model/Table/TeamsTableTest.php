<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TeamsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TeamsTable Test Case
 */
class TeamsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TeamsTable
     */
    public $Teams;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.teams',
        'app.users',
        'app.championships',
        'app.leagues',
        'app.seasons',
        'app.matchdays',
        'app.articles',
        'app.lineups',
        'app.members',
        'app.players',
        'app.roles',
        'app.clubs',
        'app.dispositions',
        'app.ratings',
        'app.members_teams',
        'app.scores',
        'app.transferts',
        'app.events',
        'app.selections'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Teams') ? [] : ['className' => 'App\Model\Table\TeamsTable'];
        $this->Teams = TableRegistry::get('Teams', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Teams);

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
