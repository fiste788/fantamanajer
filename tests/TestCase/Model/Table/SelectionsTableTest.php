<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SelectionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SelectionsTable Test Case
 */
class SelectionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SelectionsTable
     */
    public $Selections;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.selections',
        'app.teams',
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
        'app.view0_max_points',
        'app.view2_teams_stats',
        'app.matchdays',
        'app.articles',
        'app.lineups',
        'app.dispositions',
        'app.ratings',
        'app.scores',
        'app.transferts',
        'app.view2_clubs_stats',
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
        $config = TableRegistry::exists('Selections') ? [] : ['className' => 'App\Model\Table\SelectionsTable'];
        $this->Selections = TableRegistry::get('Selections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Selections);

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
