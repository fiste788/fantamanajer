<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PushSubscriptionsTable;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * App\Model\Table\PushSubscriptionsTable Test Case
 */
class PushSubscriptionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PushSubscriptionsTable
     */
    protected $PushSubscriptions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.PushSubscriptions',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PushSubscriptions') ? [] : ['className' => PushSubscriptionsTable::class];
        $this->PushSubscriptions = TableRegistry::getTableLocator()->get('PushSubscriptions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->PushSubscriptions);

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
        $validation = $this->PushSubscriptions->validationDefault(new Validator());
        $this->assertNotNull($validation);
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $rules = $this->PushSubscriptions->buildRules(new RulesChecker());
        $this->assertNotNull($rules);
    }

    /**
     * Test beforeMarshal method
     *
     * @return void
     */
    public function testBeforeMarshal(): void
    {
        $data = new \ArrayObject([
            'created_at' => new FrozenTime(),
            'modified_at' => new FrozenTime(),
        ]);
        $this->PushSubscriptions->beforeMarshal(new Event('', null, null), $data, new \ArrayObject());
        $this->assertArrayNotHasKey('created_at', $data, 'Created_at non unsetted');
        $this->assertArrayNotHasKey('modified_at', $data, 'Modified_at non unsetted');
    }

    /**
     * Test beforeSave method
     *
     * @return void
     */
    public function testBeforeSave(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
