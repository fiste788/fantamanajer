<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PublicKeyCredentialSourcesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PublicKeyCredentialSourcesTable Test Case
 */
class PublicKeyCredentialSourcesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PublicKeyCredentialSourcesTable
     */
    public $PublicKeyCredentialSources;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PublicKeyCredentialSources'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PublicKeyCredentialSources') ? [] : ['className' => PublicKeyCredentialSourcesTable::class];
        $this->PublicKeyCredentialSources = TableRegistry::getTableLocator()->get('PublicKeyCredentialSources', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PublicKeyCredentialSources);

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
