<?php

declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClubsTable;
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClubsTable Test Case
 */
class ClubsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ClubsTable
     */
    protected $Clubs;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Clubs',
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
        $config = TableRegistry::getTableLocator()->exists('Clubs') ? [] : ['className' => ClubsTable::class];
        $this->Clubs = TableRegistry::getTableLocator()->get('Clubs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Clubs);

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
     * Test findBySeasonId method
     *
     * @return void
     */
    public function testFindBySeasonId(): void
    {
        $query = $this->Clubs->find('bySeasonId', ['season_id' => 16]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $clubs = $query->toArray();
        $this->assertNotEmpty($clubs, 'Clubs not present');
    }
}
