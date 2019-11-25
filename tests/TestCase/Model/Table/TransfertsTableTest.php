<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransfertsTable;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

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
        $validation = $this->Transferts->validationDefault(new Validator());
        $this->assertNotNull($validation);
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $rules = $this->Transferts->buildRules(new RulesChecker());
        $this->assertNotNull($rules);
    }

    /**
     * Test beforeMarshal method
     *
     * @return void
     */
    public function testBeforeMarshal(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findByTeamId method
     *
     * @return void
     */
    public function testFindByTeamId(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test beforeSave method
     *
     * @return void
     */
    public function testBeforeSave(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test afterSave method
     *
     * @return void
     */
    public function testAfterSave(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test loadService method
     *
     * @return void
     */
    public function testLoadService(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getServiceLocator method
     *
     * @return void
     */
    public function testGetServiceLocator(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test setServiceLocator method
     *
     * @return void
     */
    public function testSetServiceLocator(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
