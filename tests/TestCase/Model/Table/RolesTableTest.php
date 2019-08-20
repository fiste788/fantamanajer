<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RolesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RolesTable Test Case
 */
class RolesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RolesTable
     */
    public $RolesTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Roles',
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
        $config = TableRegistry::getTableLocator()->exists('Roles') ? [] : ['className' => RolesTable::class];
        $this->RolesTable = TableRegistry::getTableLocator()->get('Roles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->RolesTable);

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
}
