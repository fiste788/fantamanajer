<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Player;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Player Test Case
 */
class PlayerTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Player
     */
    public $Player;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Player = new Player();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Player);

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
