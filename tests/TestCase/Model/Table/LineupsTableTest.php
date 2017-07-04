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
    public $Lineups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.lineups',
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
        'app.ratings',
        'app.scores',
        'app.lineup',
        'app.transferts',
        'app.events',
        'app.selections',
        'app.view1_matchday_win',
        'app.members_teams',
        'app.view0_max_points',
        'app.view2_clubs_stats',
        'app.dispositions',
        'app.vw_members_stats'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Lineups') ? [] : ['className' => LineupsTable::class];
        $this->Lineups = TableRegistry::get('Lineups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Lineups);

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
     * Test findStatsByMatchdayAndTeam method
     *
     * @return void
     */
    public function testFindStatsByMatchdayAndTeam()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
