<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Matchday;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Matchday Test Case
 */
class MatchdayTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\Matchday
     */
    public $Matchday;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Matchday = new Matchday();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Matchday);

        parent::tearDown();
    }

    /**
     * Test isWeeklyScriptDay method
     *
     * @return void
     */
    public function testIsWeeklyScriptDay()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test isDoTransertDay method
     *
     * @return void
     */
    public function testIsDoTransertDay()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test isSendMailDay method
     *
     * @return void
     */
    public function testIsSendMailDay()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
