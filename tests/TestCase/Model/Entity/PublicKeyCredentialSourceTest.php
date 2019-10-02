<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\PublicKeyCredentialSource;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\PublicKeyCredentialSources Test Case
 */
class PublicKeyCredentialSourceTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\PublicKeyCredentialSource
     */
    public $PublicKeyCredentialSource;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->PublicKeyCredentialSource = new PublicKeyCredentialSource();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->PublicKeyCredentialSource);

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
