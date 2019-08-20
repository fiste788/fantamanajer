<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\League;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\League Test Case
 */
class LeagueTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\League
     */
    public $League;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->League = new League();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->League);

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
