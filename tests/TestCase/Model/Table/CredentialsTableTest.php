<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CredentialsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CredentialsTable Test Case
 */
class CredentialsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CredentialsTable
     */
    public $Credentials;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Credentials',
        'app.AttestedCredentials',
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
        $config = TableRegistry::getTableLocator()->exists('Credentials') ? [] : ['className' => CredentialsTable::class];
        $this->Credentials = TableRegistry::getTableLocator()->get('Credentials', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Credentials);

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
