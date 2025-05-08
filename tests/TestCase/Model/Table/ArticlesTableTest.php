<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArticlesTable;
use ArrayObject;
use Cake\Event\Event;
use Cake\I18n\DateTime;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

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
        $validation = $this->Articles->validationDefault(new Validator());
        $this->assertNotNull($validation);
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
        $data = new ArrayObject([
            'created_at' => new DateTime(),
            'modified_at' => new DateTime(),
        ]);
        $this->Articles->beforeMarshal(new Event('', null, []), $data, new ArrayObject());
        $this->assertArrayNotHasKey('created_at', $data, 'Created_at non unsetted');
        $this->assertArrayNotHasKey('modified_at', $data, 'Modified_at non unsetted');
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
