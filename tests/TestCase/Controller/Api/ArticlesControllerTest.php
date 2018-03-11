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
        'app.championships',
        'app.leagues',
        'app.seasons',
        'app.matchdays',
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
        'app.selections',
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
