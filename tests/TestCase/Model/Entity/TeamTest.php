<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Team;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Team Test Case
 */
class TeamTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\Team
     */
    public $Team;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Team = new Team();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Team);

        parent::tearDown();
    }

    /**
     * Test isNotificationSubscripted method
     *
     * @return void
     */
    public function testIsNotificationSubscripted()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test isEmailSubscripted method
     *
     * @return void
     */
    public function testIsEmailSubscripted()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test isPushSubscripted method
     *
     * @return void
     */
    public function testIsPushSubscripted()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
