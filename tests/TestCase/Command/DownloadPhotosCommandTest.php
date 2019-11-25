<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command;

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Command\DownloadPhotosCommand Test Case
 *
 * @uses \App\Command\DownloadPhotosCommand
 */
class DownloadPhotosCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test execute method
     *
     * @return void
     */
    public function testExecute(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getCurrentMatchday method
     *
     * @return void
     */
    public function testGetCurrentMatchday(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
