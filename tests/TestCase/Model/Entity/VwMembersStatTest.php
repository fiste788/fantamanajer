<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\VwMembersStat;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\VwMembersStat Test Case
 */
class VwMembersStatTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\VwMembersStat
     */
    public $VwMembersStat;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->VwMembersStat = new VwMembersStat();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->VwMembersStat);

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
