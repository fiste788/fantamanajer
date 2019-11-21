<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MatchdaysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MatchdaysTable Test Case
 */
class MatchdaysTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MatchdaysTable
     */
    protected $Matchdays;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Matchdays',
        'app.Seasons',
        'app.Articles',
        'app.Lineups',
        'app.Ratings',
        'app.Scores',
        'app.Selections',
        'app.Transferts',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Matchdays') ? [] : ['className' => MatchdaysTable::class];
        $this->Matchdays = TableRegistry::getTableLocator()->get('Matchdays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Matchdays);

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
