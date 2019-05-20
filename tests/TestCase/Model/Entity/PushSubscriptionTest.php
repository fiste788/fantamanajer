<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\PushSubscription;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\PushSubscription Test Case
 */
class PushSubscriptionTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\PushSubscription
     */
    public $PushSubscription;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->PushSubscription = new PushSubscription();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->PushSubscription);

        parent::tearDown();
    }

    /**
     * Test getSubscription method
     *
     * @return void
     */
    public function testGetSubscription()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
