<?php

declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RolesFixture
 */
class RolesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'singular' => ['type' => 'string', 'length' => 32, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'plural' => ['type' => 'string', 'length' => 32, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'abbreviation' => ['type' => 'string', 'length' => 32, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'determinant' => ['type' => 'string', 'length' => 5, 'null' => false, 'default' => 'il', 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => '1',
                'singular' => 'Portiere',
                'plural' => 'Portieri',
                'abbreviation' => 'P',
                'determinant' => 'il',
            ],
            [
                'id' => '2',
                'singular' => 'Difensore',
                'plural' => 'Difensori',
                'abbreviation' => 'D',
                'determinant' => 'il',
            ],
            [
                'id' => '3',
                'singular' => 'Centrocampista',
                'plural' => 'Centrocampisti',
                'abbreviation' => 'C',
                'determinant' => 'il',
            ],
            [
                'id' => '4',
                'singular' => 'Attaccante',
                'plural' => 'Attaccanti',
                'abbreviation' => 'A',
                'determinant' => 'l\'',
            ],
        ];
        parent::init();
    }
}
