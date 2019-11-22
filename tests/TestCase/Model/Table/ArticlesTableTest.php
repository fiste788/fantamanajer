<?php

declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArticlesTable;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArticlesTable Test Case
 */
class ArticlesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ArticlesTable
     */
    protected $Articles;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Articles',
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
        $config = TableRegistry::getTableLocator()->exists('Articles') ? [] : ['className' => ArticlesTable::class];
        $this->Articles = TableRegistry::getTableLocator()->get('Articles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Articles);

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
        $rules = $this->Articles->buildRules(new RulesChecker());
        $this->assertNotNull($rules);
    }

    /**
     * Test beforeMarshal method
     *
     * @return void
     */
    public function testBeforeMarshal(): void
    {
        $data = $this->Articles->newEmptyEntity(['created_at' => new FrozenTime(), 'updated_at' => new FrozenTime()]);

        $this->Articles->beforeMarshal(new Event('', null, null), new \ArrayObject($data->toArray()), new \ArrayObject());
        $this->assertArrayNotHasKey('created_at', $data, 'Created_at non unsetted');
        $this->assertArrayNotHasKey('updated_at', $data, 'Updated_at non unsetted');
    }

    /**
     * Test findByChampionshipId method
     *
     * @return void
     */
    public function testFindByChampionshipId(): void
    {
        $query = $this->Articles->find('byChampionshipId', ['championship_id' => 14]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $articles = $query->toArray();
        $this->assertNotEmpty($articles);
    }

    /**
     * Test findByTeamId method
     *
     * @return void
     */
    public function testFindByTeamId(): void
    {
        $query = $this->Articles->find('byTeamId', ['team_id' => 55]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $articles = $query->toArray();
        $this->assertNotEmpty($articles);
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
