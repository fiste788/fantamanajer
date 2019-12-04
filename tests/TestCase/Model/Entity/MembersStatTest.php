<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\MembersStat;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\MembersStat Test Case
 */
class MembersStatTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\MembersStat
     */
    public $MembersStat;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->MembersStat = new MembersStat();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->MembersStat);

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
