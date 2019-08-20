<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Member;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Member Test Case
 */
class MemberTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\Member
     */
    public $Member;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Member = new Member();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Member);

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
