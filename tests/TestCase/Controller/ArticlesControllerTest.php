<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ArticlesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ArticlesController Test Case
 */
class ArticlesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.articles',
        'app.teams',
        'app.users',
        'app.view2_teams_stats',
        'app.championships',
        'app.leagues',
        'app.seasons',
        'app.matchdays',
        'app.lineups',
        'app.members',
        'app.players',
        'app.view0_lineups_details',
        'app.view0_members',
        'app.view1_members_stats',
        'app.roles',
        'app.clubs',
        'app.dispositions',
        'app.ratings',
        'app.view0_members_only_stats',
        'app.members_teams',
        'app.scores',
        'app.transferts',
        'app.view0_max_points',
        'app.view2_clubs_stats',
        'app.events',
        'app.selections',
        'app.view1_matchday_win'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
