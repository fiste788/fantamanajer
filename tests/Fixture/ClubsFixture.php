<?php

declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ClubsFixture
 */
class ClubsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 15, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'partitive' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => 'del', 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'determinant' => ['type' => 'string', 'length' => 3, 'null' => false, 'default' => 'il', 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'name' => ['type' => 'index', 'columns' => ['name'], 'length' => []],
        ],
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
                'name' => 'Atalanta',
                'partitive' => 'dell\'',
                'determinant' => 'l\'',
            ],
            [
                'id' => '2',
                'name' => 'Bologna',
                'partitive' => 'del',
                'determinant' => 'il',
            ],
            [
                'id' => '3',
                'name' => 'Cagliari',
                'partitive' => 'del',
                'determinant' => 'il',
            ],
            ['id' => '4', 'name' => 'Catania', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '5', 'name' => 'Cesena', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '6', 'name' => 'Chievo', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '4'],
            ['id' => '7', 'name' => 'Fiorentina', 'partitive' => 'della', 'determinant' => 'la', 'club_id' => '6'],
            ['id' => '8', 'name' => 'Genoa', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '7'],
            ['id' => '9', 'name' => 'Inter', 'partitive' => 'dell\'', 'determinant' => 'l\'', 'club_id' => '8'],
            ['id' => '10', 'name' => 'Juventus', 'partitive' => 'della', 'determinant' => 'la', 'club_id' => '9'],
            ['id' => '11', 'name' => 'Lazio', 'partitive' => 'della', 'determinant' => 'la', 'club_id' => '10'],
            ['id' => '12', 'name' => 'Lecce', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '13', 'name' => 'Milan', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '11'],
            ['id' => '14', 'name' => 'Napoli', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '12'],
            ['id' => '15', 'name' => 'Novara', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '16', 'name' => 'Palermo', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '17', 'name' => 'Parma', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '18', 'name' => 'Roma', 'partitive' => 'della', 'determinant' => 'la', 'club_id' => '15'],
            ['id' => '19', 'name' => 'Siena', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '20', 'name' => 'Udinese', 'partitive' => 'dell\'', 'determinant' => 'l\'', 'club_id' => '19'],
            ['id' => '21', 'name' => 'Pescara', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '22', 'name' => 'Torino', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '18'],
            ['id' => '23', 'name' => 'Sampdoria', 'partitive' => 'della', 'determinant' => 'la', 'club_id' => '16'],
            ['id' => '24', 'name' => 'Livorno', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '25', 'name' => 'Sassuolo', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '17'],
            ['id' => '26', 'name' => 'Verona', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '20'],
            ['id' => '27', 'name' => 'Empoli', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '28', 'name' => 'Carpi', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '29', 'name' => 'Frosinone', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '0'],
            ['id' => '30', 'name' => 'Crotone', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '22'],
            ['id' => '31', 'name' => 'Benevento', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '21'],
            ['id' => '32', 'name' => 'Spal', 'partitive' => 'del', 'determinant' => 'il', 'club_id' => '24'],
        ];
        parent::init();
    }
}
