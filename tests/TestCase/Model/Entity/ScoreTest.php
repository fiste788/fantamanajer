<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Score;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Score Test Case
 */
class ScoreTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Score
     */
    public $Score;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Score = new Score();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Score);

        parent::tearDown();
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
