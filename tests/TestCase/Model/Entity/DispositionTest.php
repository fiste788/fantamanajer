<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Disposition;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Disposition Test Case
 */
class DispositionTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Disposition
     */
    public $Disposition;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Disposition = new Disposition();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Disposition);

        parent::tearDown();
    }

    /**
     * Test regularize method
     *
     * @return void
     */
    public function testRegularize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test reset method
     *
     * @return void
     */
    public function testReset()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
