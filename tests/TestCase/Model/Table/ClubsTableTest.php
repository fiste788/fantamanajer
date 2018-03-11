<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClubsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClubsTable Test Case
 */
class ClubsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ClubsTable
     */
    public $Clubs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.clubs',
        'app.members'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Clubs') ? [] : ['className' => 'App\Model\Table\ClubsTable'];
        $this->Clubs = TableRegistry::get('Clubs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Clubs);

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
