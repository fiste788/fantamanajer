<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChampionshipsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChampionshipsTable Test Case
 */
class ChampionshipsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ChampionshipsTable
     */
    public $ChampionshipsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Championships',
        'app.Leagues',
        'app.Seasons',
        'app.Teams',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Championships') ? [] : ['className' => ChampionshipsTable::class];
        $this->ChampionshipsTable = TableRegistry::getTableLocator()->get('Championships', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ChampionshipsTable);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
