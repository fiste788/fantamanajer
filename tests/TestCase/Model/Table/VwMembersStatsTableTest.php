<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VwMembersStatsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VwMembersStatsTable Test Case
 */
class VwMembersStatsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VwMembersStatsTable
     */
    public $VwMembersStats;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vw_members_stats',
        'app.members',
        'app.players',
        'app.roles',
        'app.view0_lineups_details',
        'app.view0_members',
        'app.view1_members_stats',
        'app.clubs',
        'app.seasons',
        'app.championships',
        'app.leagues',
        'app.teams',
        'app.users',
        'app.view2_teams_stats',
        'app.articles',
        'app.matchdays',
        'app.lineups',
        'app.dispositions',
        'app.ratings',
        'app.scores',
        'app.transferts',
        'app.events',
        'app.selections',
        'app.view1_matchday_win',
        'app.members_teams',
        'app.view0_max_points',
        'app.view2_clubs_stats'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('VwMembersStats') ? [] : ['className' => 'App\Model\Table\VwMembersStatsTable'];
        $this->VwMembersStats = TableRegistry::get('VwMembersStats', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->VwMembersStats);

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
