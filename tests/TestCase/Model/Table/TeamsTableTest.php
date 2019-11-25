<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TeamsTable;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * App\Model\Table\TeamsTable Test Case
 */
class TeamsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TeamsTable
     */
    protected $Teams;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Teams',
        'app.Users',
        'app.Championships',
        'app.Articles',
        'app.Lineups',
        'app.NotificationSubscriptions',
        'app.Scores',
        'app.Selections',
        'app.Transferts',
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
        $config = TableRegistry::getTableLocator()->exists('Teams') ? [] : ['className' => TeamsTable::class];
        $this->Teams = TableRegistry::getTableLocator()->get('Teams', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Teams);

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
        $validation = $this->Teams->validationDefault(new Validator());
        $this->assertNotNull($validation);
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $rules = $this->Teams->buildRules(new RulesChecker());
        $this->assertNotNull($rules);
    }

    /**
     * Test findByChampionshipId method
     *
     * @return void
     */
    public function testFindByChampionshipId(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test saveWithoutUser method
     *
     * @return void
     */
    public function testSaveWithoutUser(): void
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
