<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LineupsFixture
 *
 */
class LineupsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'module' => ['type' => 'string', 'length' => 7, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'jolly' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'cloned' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created_at' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => '', 'precision' => null],
        'modified_at' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => '', 'precision' => null],
        'captain_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'vcaptain_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'vvcaptain_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'matchday_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'team_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'old_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'jolly' => ['type' => 'index', 'columns' => ['jolly'], 'length' => []],
            'vcaptain_id' => ['type' => 'index', 'columns' => ['vcaptain_id'], 'length' => []],
            'vvcaptain_id' => ['type' => 'index', 'columns' => ['vvcaptain_id'], 'length' => []],
            'team_id' => ['type' => 'index', 'columns' => ['team_id'], 'length' => []],
            'captain_id' => ['type' => 'index', 'columns' => ['captain_id'], 'length' => []],
            'matchday_id' => ['type' => 'index', 'columns' => ['matchday_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'lineups_ibfk_1' => ['type' => 'foreign', 'columns' => ['captain_id'], 'references' => ['members', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'lineups_ibfk_2' => ['type' => 'foreign', 'columns' => ['vcaptain_id'], 'references' => ['members', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'lineups_ibfk_3' => ['type' => 'foreign', 'columns' => ['vvcaptain_id'], 'references' => ['members', 'id'], 'update' => 'cascade', 'delete' => 'setNull', 'length' => []],
            'lineups_ibfk_4' => ['type' => 'foreign', 'columns' => ['matchday_id'], 'references' => ['matchdays', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'lineups_ibfk_5' => ['type' => 'foreign', 'columns' => ['team_id'], 'references' => ['teams', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
            [
                'id' => 1,
                'module' => 'Lorem',
                'jolly' => 1,
                'cloned' => 1,
                'created_at' => 1536228855,
                'modified_at' => 1536228855,
                'captain_id' => 1,
                'vcaptain_id' => 1,
                'vvcaptain_id' => 1,
                'matchday_id' => 1,
                'team_id' => 1,
                'old_id' => 1
            ],
        ];
        parent::init();
    }
}
