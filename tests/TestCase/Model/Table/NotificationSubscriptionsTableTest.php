<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NotificationSubscriptionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NotificationSubscriptionsTable Test Case
 */
class NotificationSubscriptionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NotificationSubscriptionsTable
     */
    public $NotificationSubscriptions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.notification_subscriptions',
        'app.teams',
        'app.users',
        'app.push_subscriptions',
        'app.view2_teams_stats',
        'app.championships',
        'app.leagues',
        'app.seasons',
        'app.matchdays',
        'app.articles',
        'app.lineups',
        'app.captain',
        'app.players',
        'app.members',
        'app.roles',
        'app.view0_lineups_details',
        'app.view0_members',
        'app.view1_members_stats',
        'app.clubs',
        'app.dispositions',
        'app.ratings',
        'app.members_teams',
        'app.vw_members_stats',
        'app.v_captain',
        'app.v_v_captain',
        'app.scores',
        'app.transferts',
        'app.new_members',
        'app.old_members',
        'app.view2_clubs_stats',
        'app.view0_max_points',
        'app.email_subscriptions',
        'app.events',
        'app.selections'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('NotificationSubscriptions') ? [] : ['className' => NotificationSubscriptionsTable::class];
        $this->NotificationSubscriptions = TableRegistry::get('NotificationSubscriptions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NotificationSubscriptions);

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
