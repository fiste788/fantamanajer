<?php
namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\ArticlesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\Api\ArticlesController Test Case
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
        'app.roles',
        'app.view0_lineups_details',
        'app.view0_members',
        'app.view1_members_stats',
        'app.clubs',
        'app.dispositions',
        'app.ratings',
        'app.members_teams',
        'app.vw_members_stats',
        'app.scores',
        'app.transferts',
        'app.view2_clubs_stats',
        'app.view0_max_points',
        'app.events',
        'app.selections',
        'app.view1_matchday_win'
    ];

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
