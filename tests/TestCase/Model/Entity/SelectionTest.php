<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Selection;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Selection Test Case
 */
class SelectionTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Selection
     */
    public $Selection;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Selection = new Selection();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Selection);

        parent::tearDown();
    }

    /**
     * Test toTransfert method
     *
     * @return void
     */
    public function testToTransfert()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
