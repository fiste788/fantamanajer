<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersUserHandlesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersUserHandlesTable Test Case
 */
class UsersUserHandlesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersUserHandlesTable
     */
    public $UsersUserHandles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsersUserHandles',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsersUserHandles') ? [] : ['className' => UsersUserHandlesTable::class];
        $this->UsersUserHandles = TableRegistry::getTableLocator()->get('UsersUserHandles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersUserHandles);

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
