<?php

declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LineupsFixture
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
        'module' => ['type' => 'string', 'length' => 7, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_unicode_ci', 'comment' => '', 'precision' => null],
        'jolly' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'cloned' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created_at' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'modified_at' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => true, 'default' => null, 'comment' => ''],
        'captain_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'vcaptain_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'vvcaptain_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'matchday_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'team_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'vcaptain_id' => ['type' => 'index', 'columns' => ['vcaptain_id'], 'length' => []],
            'vvcaptain_id' => ['type' => 'index', 'columns' => ['vvcaptain_id'], 'length' => []],
            'team_id' => ['type' => 'index', 'columns' => ['team_id'], 'length' => []],
            'captain_id' => ['type' => 'index', 'columns' => ['captain_id'], 'length' => []],
            'matchday_id' => ['type' => 'index', 'columns' => ['matchday_id'], 'length' => []],
            'jolly' => ['type' => 'index', 'columns' => ['jolly'], 'length' => []],
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
    public function init(): void
    {
        $this->records = [
            [
                'id' => '1781',
                'module' => '1-3-4-3',
                'jolly' => null,
                'cloned' => null,
                'created_at' => '2018-09-28 23:05:34',
                'modified_at' => '2018-10-08 15:06:07',
                'captain_id' => '4754',
                'vcaptain_id' => '4762',
                'vvcaptain_id' => '4777',
                'matchday_id' => '576',
                'team_id' => '55',
            ],
        ];
        parent::init();
    }
}
