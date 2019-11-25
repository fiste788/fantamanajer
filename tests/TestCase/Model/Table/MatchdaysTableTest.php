<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MatchdaysTable;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

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
        $validation = $this->Matchdays->validationDefault(new Validator());
        $this->assertNotNull($validation);
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $rules = $this->Matchdays->buildRules(new RulesChecker());
        $this->assertNotNull($rules);
    }

    /**
     * Test findCurrent method
     *
     * @return void
     */
    public function testFindCurrent(): void
    {
        $query = $this->Matchdays->find('current');
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $matchday = $query->first();
        $this->assertEquals($matchday->id, 577, 'Current matchday not matching');
    }

    /**
     * Test findPrevious method
     *
     * @return void
     */
    public function testFindPrevious(): void
    {
        $query = $this->Matchdays->find('previous');
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $matchday = $query->first();
        $this->assertEquals($matchday->id, 576, 'Previous matchday not matching');
    }

    /**
     * Test findWithoutScores method
     *
     * @return void
     */
    public function testFindWithoutScores(): void
    {
        $season = $this->Matchdays->Seasons->get(16);
        $matchdays = $this->Matchdays->findWithoutScores($season);
        $this->assertNotEmpty($matchdays, 'No matchdays without scores');
    }

    /**
     * Test findWithScores method
     *
     * @return void
     */
    public function testFindWithScores(): void
    {
        $season = $this->Matchdays->Seasons->get(16);
        $matchdays = $this->Matchdays->findWithScores($season);
        $this->assertNotEmpty($matchdays, 'No matchdays without scores');
    }

    /**
     * Test findFirstWithoutScores method
     *
     * @return void
     */
    public function testFindFirstWithoutScores(): void
    {
        $query = $this->Matchdays->find('firstWithoutScores', ['season' => 16]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $matchday = $query->first();
        $this->assertEquals($matchday->id, 571, 'Previous matchday not matching');
    }

    /**
     * Test findWithoutRatings method
     *
     * @return void
     */
    public function testFindWithoutRatings(): void
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
