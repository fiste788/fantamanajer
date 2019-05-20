<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Championship;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Championship Test Case
 */
class ChampionshipTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Championship
     */
    public $Championship;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Championship = new Championship();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Championship);

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
