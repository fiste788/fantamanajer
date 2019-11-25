<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MembersTable;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * App\Model\Table\MembersTable Test Case
 */
class MembersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MembersTable
     */
    protected $MembersTable;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Members',
        'app.Players',
        'app.Roles',
        'app.Clubs',
        'app.Seasons',
        'app.Dispositions',
        'app.Ratings',
        'app.Teams',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Members') ? [] : ['className' => MembersTable::class];
        $this->MembersTable = TableRegistry::getTableLocator()->get('Members', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->MembersTable);

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
        $validation = $this->MembersTable->validationDefault(new Validator());
        $this->assertNotNull($validation);
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $rules = $this->MembersTable->buildRules(new RulesChecker());
        $this->assertNotNull($rules);
    }

    /**
     * Test findListBySeasonId method
     *
     * @return void
     */
    public function testFindListBySeasonId(): void
    {
        $result = $this->MembersTable->findListBySeasonId(6);
        $this->assertNotEmpty($result, 'Members not found');
    }

    /**
     * Test findWithStats method
     *
     * @return void
     */
    public function testFindWithStats(): void
    {
        $query = $this->MembersTable->find('withStats', ['season_id' => 6]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->toArray();
        $this->assertNotEmpty($result, 'Stats not found');
    }

    /**
     * Test findWithDetails method
     *
     * @return void
     */
    public function testFindWithDetails(): void
    {
        $query = $this->MembersTable->find('withDetails');
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->toArray();
        $this->assertNotEmpty($result, 'Details not found');
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findFree method
     *
     * @return void
     */
    public function testFindFree(): void
    {
        $query = $this->MembersTable->find('free', [
            'championship_id' => 16,
            'stats' => true,
            'role' => 1,
        ]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->toArray();
        $this->assertNotEmpty($result, 'Free not found');
    }

    /**
     * Test findByClubId method
     *
     * @return void
     */
    public function testFindByClubId(): void
    {
        $query = $this->MembersTable->find('byClubId', [
            'club_id' => 1,
            'season_id' => 6,
        ]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->toArray();
        $this->assertNotEmpty($result, 'By club id empty');
    }

    /**
     * Test findByTeamId method
     *
     * @return void
     */
    public function testFindByTeamId(): void
    {
        $query = $this->MembersTable->find('byTeamId', [
            'team_id' => 55,
            'role_id' => 1,
        ]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->toArray();
        $this->assertNotEmpty($result, 'By team id empty');
    }

    /**
     * Test findNotMine method
     *
     * @return void
     */
    public function testFindNotMine(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findBestByMatchdayIdAndRole method
     *
     * @return void
     */
    public function testFindBestByMatchdayIdAndRole(): void
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
}
