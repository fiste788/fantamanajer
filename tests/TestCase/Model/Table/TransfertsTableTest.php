<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransfertsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TransfertsTable Test Case
 */
class TransfertsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TransfertsTable
     */
    public $Transferts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.transferts',
        'app.members',
        'app.players',
        'app.view0_lineups_details',
        'app.view0_members',
        'app.view1_members_stats',
        'app.roles',
        'app.clubs',
        'app.seasons',
        'app.championships',
        'app.leagues',
        'app.teams',
        'app.users',
        'app.articles',
        'app.matchdays',
        'app.lineups',
        'app.dispositions',
        'app.ratings',
        'app.scores',
        'app.view0_max_points',
        'app.events',
        'app.selections',
        'app.view1_matchday_win',
        'app.members_teams',
        'app.view2_teams_stats',
        'app.view2_clubs_stats',
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
        $config = TableRegistry::exists('Transferts') ? [] : ['className' => 'App\Model\Table\TransfertsTable'];
        $this->Transferts = TableRegistry::get('Transferts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Transferts);

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
