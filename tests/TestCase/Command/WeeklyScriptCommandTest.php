<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command;

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Command\WeeklyScriptCommand Test Case
 *
 * @uses \App\Command\WeeklyScriptCommand
 */
class WeeklyScriptCommandTest extends TestCase
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
     * Test buildOptionParser method
     *
     * @return void
     */
    public function testBuildOptionParser(): void
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
     * Test sendScoreNotifications method
     *
     * @return void
     */
    public function testSendScoreNotifications(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test sendScoreMails method
     *
     * @return void
     */
    public function testSendScoreMails(): void
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

    /**
     * Test loadService method
     *
     * @return void
     */
    public function testLoadService(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getServiceLocator method
     *
     * @return void
     */
    public function testGetServiceLocator(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test setServiceLocator method
     *
     * @return void
     */
    public function testSetServiceLocator(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
