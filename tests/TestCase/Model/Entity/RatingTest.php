<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Rating;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Rating Test Case
 */
class RatingTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Rating
     */
    public $Rating;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Rating = new Rating();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Rating);

        parent::tearDown();
    }

    /**
     * Test calcNoBonusPoints method
     *
     * @return void
     */
    public function testCalcNoBonusPoints()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
