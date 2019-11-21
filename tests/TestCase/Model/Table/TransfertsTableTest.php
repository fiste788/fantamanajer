<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransfertsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TransfertsTable Test Case
 */
class TransfertsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TransfertsTable
     */
    protected $Transferts;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Transferts',
        'app.Members',
        'app.Teams',
        'app.Matchdays',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Transferts') ? [] : ['className' => TransfertsTable::class];
        $this->Transferts = TableRegistry::getTableLocator()->get('Transferts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Transferts);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
