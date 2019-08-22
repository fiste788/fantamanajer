<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ChampionshipsFixture
 *
 */
class ChampionshipsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'captain' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'number_transferts' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '15', 'comment' => '', 'precision' => null],
        'number_selections' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => '2', 'comment' => '', 'precision' => null],
        'minute_lineup' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => false, 'default' => '10', 'comment' => '', 'precision' => null],
        'points_missed_lineup' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => false, 'default' => '66', 'comment' => '', 'precision' => null],
        'captain_missed_lineup' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'jolly' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '1', 'comment' => '', 'precision' => null],
        'started' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '1', 'comment' => '', 'precision' => null],
        'league_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'season_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'league_id' => ['type' => 'index', 'columns' => ['league_id'], 'length' => []],
            'season_id' => ['type' => 'index', 'columns' => ['season_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'league_id_2' => ['type' => 'unique', 'columns' => ['league_id', 'season_id'], 'length' => []],
            'championships_ibfk_1' => ['type' => 'foreign', 'columns' => ['league_id'], 'references' => ['leagues', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'championships_ibfk_2' => ['type' => 'foreign', 'columns' => ['season_id'], 'references' => ['seasons', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
    public function init()
    {
        $this->records = [
        ['id' => '14', 'captain' => '1', 'number_transferts' => '15', 'number_selections' => '2', 'minute_lineup' => '10', 'points_missed_lineup' => '100', 'captain_missed_lineup' => '0', 'started' => '1', 'jolly' => '1', 'league_id' => '1', 'season_id' => '16']
        ];
        parent::init();
    }
}
