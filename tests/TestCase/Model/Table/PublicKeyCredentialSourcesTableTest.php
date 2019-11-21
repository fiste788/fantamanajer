<?php
declare(strict_types=1);

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
    protected $PublicKeyCredentialSources;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.PublicKeyCredentialSources',
        'app.PublicKeyCredentials',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
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
    public function tearDown(): void
    {
        unset($this->PublicKeyCredentialSources);

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
