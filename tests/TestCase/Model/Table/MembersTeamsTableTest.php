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
    public $MembersTeams;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.members_teams',
        'app.teams',
        'app.members',
        'app.players',
        'app.roles',
        'app.clubs',
        'app.view0_lineups_details',
        'app.view0_members',
        'app.view1_members_stats',
        'app.seasons',
        'app.dispositions',
        'app.lineups',
        'app.matchdays',
        'app.articles',
        'app.ratings',
        'app.scores',
        'app.transferts',
        'app.view0_max_points',
        'app.view0_members_only_stats'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MembersTeams') ? [] : ['className' => 'App\Model\Table\MembersTeamsTable'];
        $this->MembersTeams = TableRegistry::get('MembersTeams', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MembersTeams);

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
