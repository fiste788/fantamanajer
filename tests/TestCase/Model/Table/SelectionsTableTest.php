<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SelectionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SelectionsTable Test Case
 */
class SelectionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SelectionsTable
     */
    protected $Selections;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Selections',
        'app.Teams',
        'app.Matchdays',
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
        $config = TableRegistry::getTableLocator()->exists('Selections') ? [] : ['className' => SelectionsTable::class];
        $this->Selections = TableRegistry::getTableLocator()->get('Selections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Selections);

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

    /**
     * Test findAlreadySelectedMember method
     *
     * @return void
     */
    public function testFindAlreadySelectedMember(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findByTeamIdAndMatchdayId method
     *
     * @return void
     */
    public function testFindByTeamIdAndMatchdayId(): void
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
     * Test beforeSave method
     *
     * @return void
     */
    public function testBeforeSave(): void
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
