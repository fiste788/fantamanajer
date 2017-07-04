<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PlayersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PlayersTable Test Case
 */
class PlayersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PlayersTable
     */
    public $Players;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.players',
        'app.members',
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
        'app.teams',
        'app.ratings',
        'app.scores',
        'app.transferts',
        'app.view0_max_points',
        'app.view0_members_only_stats',
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
        $config = TableRegistry::exists('Players') ? [] : ['className' => 'App\Model\Table\PlayersTable'];
        $this->Players = TableRegistry::get('Players', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Players);

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
