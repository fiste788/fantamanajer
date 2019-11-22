<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ScoresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ScoresTable Test Case
 */
class ScoresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ScoresTable
     */
    protected $Scores;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Scores',
        'app.Lineups',
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
        $config = TableRegistry::getTableLocator()->exists('Scores') ? [] : ['className' => ScoresTable::class];
        $this->Scores = TableRegistry::getTableLocator()->get('Scores', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Scores);

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
     * Test findMaxMatchday method
     *
     * @return void
     */
    public function testFindMaxMatchday(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findScores method
     *
     * @return void
     */
    public function testFindScores(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findByTeam method
     *
     * @return void
     */
    public function testFindByTeam(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findRanking method
     *
     * @return void
     */
    public function testFindRanking(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test loadDetails method
     *
     * @return void
     */
    public function testLoadDetails(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
