<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Transfert;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Transfert Test Case
 */
class TransfertTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\Transfert
     */
    public $Transfert;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Transfert = new Transfert();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Transfert);

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
