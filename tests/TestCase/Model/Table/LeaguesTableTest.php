<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LeaguesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LeaguesTable Test Case
 */
class LeaguesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LeaguesTable
     */
    protected $Leagues;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Leagues',
        'app.Championships',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Leagues') ? [] : ['className' => LeaguesTable::class];
        $this->Leagues = TableRegistry::getTableLocator()->get('Leagues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Leagues);

        parent::tearDown();
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
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
