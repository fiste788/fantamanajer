<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MembersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

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
    public $MembersTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Members',
        'app.Players',
        'app.Roles',
        'app.Clubs',
        'app.Seasons',
        'app.Dispositions',
        'app.Ratings',
        'app.Teams'
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
     * Test findListBySeasonId method
     *
     * @return void
     */
    public function testFindListBySeasonId()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findWithStats method
     *
     * @return void
     */
    public function testFindWithStats()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findWithDetails method
     *
     * @return void
     */
    public function testFindWithDetails()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findFree method
     *
     * @return void
     */
    public function testFindFree()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findByClubId method
     *
     * @return void
     */
    public function testFindByClubId()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findByTeamId method
     *
     * @return void
     */
    public function testFindByTeamId()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findBestByMatchdayIdAndRole method
     *
     * @return void
     */
    public function testFindBestByMatchdayIdAndRole()
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
}
