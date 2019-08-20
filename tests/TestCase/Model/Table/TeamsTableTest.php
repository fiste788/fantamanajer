<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TeamsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TeamsTable Test Case
 */
class TeamsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TeamsTable
     */
    public $TeamsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Teams',
        'app.Users',
        'app.Championships',
        'app.Articles',
        'app.NotificationSubscriptions',
        'app.Lineups',
        'app.Scores',
        'app.Selections',
        'app.Transferts',
        'app.Members',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Teams') ? [] : ['className' => TeamsTable::class];
        $this->TeamsTable = TableRegistry::getTableLocator()->get('Teams', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->TeamsTable);

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

    /**
     * Test findByChampionshipId method
     *
     * @return void
     */
    public function testFindByChampionshipId()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
