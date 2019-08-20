<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\Article;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\Article Test Case
 */
class ArticleTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Entity\Article
     */
    public $Article;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Article = new Article();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Article);

        parent::tearDown();
    }

    /**
     * Test isOwnedBy method
     *
     * @return void
     */
    public function testIsOwnedBy()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
