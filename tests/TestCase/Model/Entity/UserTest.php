<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\User;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\User Test Case
 */
class UserTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\User
     */
    public $User;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->User = new User();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->User);

        parent::tearDown();
    }

    /**
     * Test hasTeam method
     *
     * @return void
     */
    public function testHasTeam()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test isInChampionship method
     *
     * @return void
     */
    public function testIsInChampionship()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test can method
     *
     * @return void
     */
    public function testCan()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test applyScope method
     *
     * @return void
     */
    public function testApplyScope()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getOriginalData method
     *
     * @return void
     */
    public function testGetOriginalData()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test setAuthorization method
     *
     * @return void
     */
    public function testSetAuthorization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getIdentifier method
     *
     * @return void
     */
    public function testGetIdentifier()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
