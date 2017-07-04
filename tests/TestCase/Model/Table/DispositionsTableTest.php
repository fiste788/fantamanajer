<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DispositionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DispositionsTable Test Case
 */
class DispositionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DispositionsTable
     */
    public $Dispositions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.dispositions',
        'app.lineups',
        'app.members',
        'app.view0_lineups_details'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Dispositions') ? [] : ['className' => 'App\Model\Table\DispositionsTable'];
        $this->Dispositions = TableRegistry::get('Dispositions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Dispositions);

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
