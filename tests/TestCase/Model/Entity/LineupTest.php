<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Lineup;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Lineup Test Case
 */
class LineupTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\Lineup
     */
    public $Lineup;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Lineup = new Lineup();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Lineup);

        parent::tearDown();
    }

    /**
     * Test copy method
     *
     * @return void
     */
    public function testCopy()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test substitute method
     *
     * @return void
     */
    public function testSubstitute()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test compute method
     *
     * @return void
     */
    public function testCompute()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
