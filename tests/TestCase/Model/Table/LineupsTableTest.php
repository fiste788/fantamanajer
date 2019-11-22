<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LineupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LineupsTable Test Case
 */
class LineupsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LineupsTable
     */
    protected $Lineups;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Lineups',
        'app.Members',
        'app.Matchdays',
        'app.Teams',
        'app.Dispositions',
        'app.Scores',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Lineups') ? [] : ['className' => LineupsTable::class];
        $this->Lineups = TableRegistry::getTableLocator()->get('Lineups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Lineups);

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
     * Test afterSave method
     *
     * @return void
     */
    public function testAfterSave(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
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
     * Test findDetails method
     *
     * @return void
     */
    public function testFindDetails(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findLast method
     *
     * @return void
     */
    public function testFindLast(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findByMatchdayIdAndTeamId method
     *
     * @return void
     */
    public function testFindByMatchdayIdAndTeamId(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findWithRatings method
     *
     * @return void
     */
    public function testFindWithRatings(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
