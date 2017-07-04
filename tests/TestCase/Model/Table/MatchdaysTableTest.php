<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MatchdaysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MatchdaysTable Test Case
 */
class MatchdaysTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MatchdaysTable
     */
    public $Matchdays;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.matchdays',
        'app.seasons',
        'app.articles',
        'app.teams',
        'app.lineups',
        'app.members',
        'app.dispositions',
        'app.view0_lineups_details',
        'app.ratings',
        'app.scores',
        'app.transferts',
        'app.view0_max_points'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Matchdays') ? [] : ['className' => 'App\Model\Table\MatchdaysTable'];
        $this->Matchdays = TableRegistry::get('Matchdays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Matchdays);

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
