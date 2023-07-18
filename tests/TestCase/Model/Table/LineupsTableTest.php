<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LineupsTable;
use ArrayObject;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

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
        $validation = $this->Lineups->validationDefault(new Validator());
        $this->assertNotNull($validation);
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $rules = $this->Lineups->buildRules(new RulesChecker());
        $this->assertNotNull($rules);
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
        $data = new ArrayObject([
            'created_at' => new FrozenTime(),
            'modified_at' => new FrozenTime(),
        ]);
        $this->Lineups->beforeMarshal(new Event('', null, null), $data, new ArrayObject());
        $this->assertArrayNotHasKey('created_at', $data, 'Created_at non unsetted');
        $this->assertArrayNotHasKey('modified_at', $data, 'Modified_at non unsetted');
    }

    /**
     * Test findDetails method
     *
     * @return void
     */
    public function testFindDetails(): void
    {
        $query = $this->Lineups->find('details', ['team_id' => 55, 'matchday_id' => 576]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->toArray();
        $this->assertNotEmpty($result, 'Lineup not found');
    }

    /**
     * Test findLast method
     *
     * @return void
     */
    public function testFindLast(): void
    {
        $matchday = $this->Lineups->Matchdays->get(577);
        $query = $this->Lineups->find('last', [
            'team_id' => 55,
            'matchday' => $matchday,
        ]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->first();
        $this->assertEquals(576, $result->matchday_id, 'Last lineup not present');
    }

    /**
     * Test findByMatchdayIdAndTeamId method
     *
     * @return void
     */
    public function testFindByMatchdayIdAndTeamId(): void
    {
        $query = $this->Lineups->find('byMatchdayIdAndTeamId', [
            'team_id' => 55,
            'matchday_id' => 576,
        ]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->toArray();
        $this->assertNotEmpty($result, 'Lineup not present');
    }

    /**
     * Test findWithRatings method
     *
     * @return void
     */
    public function testFindWithRatings(): void
    {
        $query = $this->Lineups->find('withRatings', [
            'team_id' => 55,
            'matchday_id' => 576,
        ]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->toArray();
        $this->assertNotEmpty($result, 'Rating not present');
    }
}
