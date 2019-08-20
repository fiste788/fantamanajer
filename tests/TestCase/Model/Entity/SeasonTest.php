<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Season;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Season Test Case
 */
class SeasonTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\Season
     */
    public $Season;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Season = new Season();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Season);

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
