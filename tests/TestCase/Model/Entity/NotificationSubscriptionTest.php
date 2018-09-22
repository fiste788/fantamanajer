<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\NotificationSubscription;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\NotificationSubscription Test Case
 */
class NotificationSubscriptionTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\NotificationSubscription
     */
    public $NotificationSubscription;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->NotificationSubscription = new NotificationSubscription();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NotificationSubscription);

        parent::tearDown();
    }

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
