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
    public $NotificationSubscriptionsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.notification_subscriptions',
        'app.teams'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('NotificationSubscriptions') ? [] : ['className' => NotificationSubscriptionsTable::class];
        $this->NotificationSubscriptionsTable = TableRegistry::getTableLocator()->get('NotificationSubscriptions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NotificationSubscriptionsTable);

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
