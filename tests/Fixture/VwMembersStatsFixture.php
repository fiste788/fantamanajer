<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * VwMembersStatsFixture
 *
 */
class VwMembersStatsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'member_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'sum_present' => ['type' => 'decimal', 'length' => 25, 'precision' => 0, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'sum_valued' => ['type' => 'decimal', 'length' => 25, 'precision' => 0, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'avg_points' => ['type' => 'float', 'length' => 19, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'avg_rating' => ['type' => 'float', 'length' => 19, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'sum_goals' => ['type' => 'decimal', 'length' => 25, 'precision' => 0, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'sum_goals_against' => ['type' => 'decimal', 'length' => 25, 'precision' => 0, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'sum_assist' => ['type' => 'decimal', 'length' => 25, 'precision' => 0, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'sum_yellow_card' => ['type' => 'decimal', 'length' => 25, 'precision' => 0, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'sum_red_card' => ['type' => 'decimal', 'length' => 25, 'precision' => 0, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'quotation' => ['type' => 'integer', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_options' => [
            'engine' => null,
            'collation' => null
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'member_id' => 1,
            'sum_present' => 1.5,
            'sum_valued' => 1.5,
            'avg_points' => 1,
            'avg_rating' => 1,
            'sum_goals' => 1.5,
            'sum_goals_against' => 1.5,
            'sum_assist' => 1.5,
            'sum_yellow_card' => 1.5,
            'sum_red_card' => 1.5,
            'quotation' => 1
        ],
    ];
}
