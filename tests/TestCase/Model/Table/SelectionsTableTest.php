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
    public $SelectionsTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        $this->SelectionsTable = TableRegistry::getTableLocator()->get('Selections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SelectionsTable);

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

    /**
     * Test afterSave method
     *
     * @return void
     */
    public function testAfterSave()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test beforeSave method
     *
     * @return void
     */
    public function testBeforeSave()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test notifyLostMember method
     *
     * @return void
     */
    public function testNotifyLostMember()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findAlreadySelectedMember method
     *
     * @return void
     */
    public function testFindAlreadySelectedMember()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findByTeamIdAndMatchdayId method
     *
     * @return void
     */
    public function testFindByTeamIdAndMatchdayId()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
