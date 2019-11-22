<?php
declare(strict_types=1);

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
    protected $Dispositions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Dispositions',
        'app.Lineups',
        'app.Members',
        'app.Ratings',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Dispositions') ? [] : ['className' => DispositionsTable::class];
        $this->Dispositions = TableRegistry::getTableLocator()->get('Dispositions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Dispositions);

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
     * Test findByMatchdayLineup method
     *
     * @return void
     */
    public function testFindByMatchdayLineup(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
