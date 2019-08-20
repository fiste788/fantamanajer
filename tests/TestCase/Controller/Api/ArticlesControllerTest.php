<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api;

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
        'app.Articles',
        'app.Teams',
        'app.Users',
        'app.Championships',
        'app.Leagues',
        'app.Seasons',
        'app.Matchdays',
        'app.Lineups',
        'app.Members',
        'app.Players',
        'app.Roles',
        'app.Clubs',
        'app.Dispositions',
        'app.Ratings',
        'app.MembersTeams',
        'app.Scores',
        'app.Transferts',
        'app.Selections',
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
