<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Role;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Role Test Case
 */
class RoleTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Role
     */
    public $Role;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Role = new Role();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Role);

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
