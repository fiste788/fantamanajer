<?php

declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RatingsFixture
 */
class RatingsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'valued' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'points' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'rating' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'goals' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'goals_against' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'goals_victory' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'goals_tie' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'assist' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'yellow_card' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'red_card' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'penalities_scored' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'penalities_taken' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'present' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'regular' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'quotation' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'member_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'matchday_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'member_id' => ['type' => 'index', 'columns' => ['member_id'], 'length' => []],
            'matchday_id' => ['type' => 'index', 'columns' => ['matchday_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'ratings_ibfk_1' => ['type' => 'foreign', 'columns' => ['member_id'], 'references' => ['members', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'ratings_ibfk_2' => ['type' => 'foreign', 'columns' => ['matchday_id'], 'references' => ['matchdays', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
            ['id' => '165493', 'valued' => '1', 'points' => '5.5', 'rating' => '6.5', 'goals' => '0', 'goals_against' => '1', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '19', 'member_id' => '4656', 'matchday_id' => '576'],
            ['id' => '166056', 'valued' => '1', 'points' => '6', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '19', 'member_id' => '4656', 'matchday_id' => '577'],
            ['id' => '165472', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '3', 'member_id' => '4635', 'matchday_id' => '576'],
            ['id' => '166035', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '3', 'member_id' => '4635', 'matchday_id' => '577'],
            ['id' => '165490', 'valued' => '1', 'points' => '5.5', 'rating' => '6.5', 'goals' => '0', 'goals_against' => '1', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '14', 'member_id' => '4653', 'matchday_id' => '576'],
            ['id' => '166053', 'valued' => '1', 'points' => '3', 'rating' => '6', 'goals' => '0', 'goals_against' => '3', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '13', 'member_id' => '4653', 'matchday_id' => '577'],
            ['id' => '165589', 'valued' => '1', 'points' => '12', 'rating' => '7.5', 'goals' => '1', 'goals_against' => '0', 'goals_victory' => '1', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '24', 'member_id' => '4754', 'matchday_id' => '576'],
            ['id' => '166152', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '24', 'member_id' => '4754', 'matchday_id' => '577'],
            ['id' => '165537', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '16', 'member_id' => '4700', 'matchday_id' => '576'],
            ['id' => '166100', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '15', 'member_id' => '4700', 'matchday_id' => '577'],
            ['id' => '165523', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '12', 'member_id' => '4686', 'matchday_id' => '576'],
            ['id' => '166086', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '12', 'member_id' => '4686', 'matchday_id' => '577'],
            ['id' => '165610', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '15', 'member_id' => '4775', 'matchday_id' => '576'],
            ['id' => '166173', 'valued' => '1', 'points' => '6', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '15', 'member_id' => '4775', 'matchday_id' => '577'],
            ['id' => '165633', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '9', 'member_id' => '4798', 'matchday_id' => '576'],
            ['id' => '166196', 'valued' => '1', 'points' => '7.5', 'rating' => '6.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '1', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '10', 'member_id' => '4798', 'matchday_id' => '577'],
            ['id' => '165597', 'valued' => '1', 'points' => '6', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '10', 'member_id' => '4762', 'matchday_id' => '576'],
            ['id' => '166160', 'valued' => '1', 'points' => '5.5', 'rating' => '5.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '10', 'member_id' => '4762', 'matchday_id' => '577'],
            ['id' => '165585', 'valued' => '1', 'points' => '6.5', 'rating' => '6.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '11', 'member_id' => '4750', 'matchday_id' => '576'],
            ['id' => '166148', 'valued' => '1', 'points' => '5.5', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '1', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '11', 'member_id' => '4750', 'matchday_id' => '577'],
            ['id' => '165612', 'valued' => '1', 'points' => '6', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '16', 'member_id' => '4777', 'matchday_id' => '576'],
            ['id' => '166175', 'valued' => '1', 'points' => '5.5', 'rating' => '5.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '15', 'member_id' => '4777', 'matchday_id' => '577'],
            ['id' => '165822', 'valued' => '1', 'points' => '5.5', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '1', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '12', 'member_id' => '4993', 'matchday_id' => '576'],
            ['id' => '166385', 'valued' => '1', 'points' => '5.5', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '1', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '12', 'member_id' => '4993', 'matchday_id' => '577'],
            ['id' => '165856', 'valued' => '1', 'points' => '5.5', 'rating' => '5.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '21', 'member_id' => '5028', 'matchday_id' => '576'],
            ['id' => '166419', 'valued' => '1', 'points' => '5.5', 'rating' => '5.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '20', 'member_id' => '5028', 'matchday_id' => '577'],
            ['id' => '165736', 'valued' => '1', 'points' => '6.5', 'rating' => '6.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '25', 'member_id' => '4903', 'matchday_id' => '576'],
            ['id' => '166299', 'valued' => '1', 'points' => '6', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '24', 'member_id' => '4903', 'matchday_id' => '577'],
            ['id' => '165879', 'valued' => '1', 'points' => '6', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '0', 'quotation' => '17', 'member_id' => '5051', 'matchday_id' => '576'],
            ['id' => '166442', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '17', 'member_id' => '5051', 'matchday_id' => '577'],
            ['id' => '165745', 'valued' => '1', 'points' => '6', 'rating' => '6.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '1', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '23', 'member_id' => '4913', 'matchday_id' => '576'],
            ['id' => '166308', 'valued' => '1', 'points' => '4.5', 'rating' => '5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '1', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '22', 'member_id' => '4913', 'matchday_id' => '577'],
            ['id' => '165775', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '0', 'quotation' => '14', 'member_id' => '4945', 'matchday_id' => '576'],
            ['id' => '166338', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '14', 'member_id' => '4945', 'matchday_id' => '577'],
            ['id' => '165834', 'valued' => '1', 'points' => '6', 'rating' => '6.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '1', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '12', 'member_id' => '5005', 'matchday_id' => '576'],
            ['id' => '166397', 'valued' => '1', 'points' => '6.5', 'rating' => '6.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '12', 'member_id' => '5005', 'matchday_id' => '577'],
            ['id' => '165788', 'valued' => '1', 'points' => '6', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '16', 'member_id' => '4958', 'matchday_id' => '576'],
            ['id' => '166351', 'valued' => '1', 'points' => '6', 'rating' => '6', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '16', 'member_id' => '4958', 'matchday_id' => '577'],
            ['id' => '165912', 'valued' => '1', 'points' => '6.5', 'rating' => '6.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '34', 'member_id' => '5084', 'matchday_id' => '576'],
            ['id' => '166475', 'valued' => '1', 'points' => '7', 'rating' => '7', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '33', 'member_id' => '5084', 'matchday_id' => '577'],
            ['id' => '165950', 'valued' => '1', 'points' => '5.5', 'rating' => '5.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '0', 'quotation' => '29', 'member_id' => '5123', 'matchday_id' => '576'],
            ['id' => '166513', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '29', 'member_id' => '5123', 'matchday_id' => '577'],
            ['id' => '165999', 'valued' => '1', 'points' => '10.5', 'rating' => '7', 'goals' => '1', 'goals_against' => '0', 'goals_victory' => '1', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '22', 'member_id' => '5176', 'matchday_id' => '576'],
            ['id' => '166562', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '22', 'member_id' => '5176', 'matchday_id' => '577'],
            ['id' => '165916', 'valued' => '1', 'points' => '5.5', 'rating' => '5.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '12', 'member_id' => '5088', 'matchday_id' => '576'],
            ['id' => '166479', 'valued' => '1', 'points' => '5', 'rating' => '5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '12', 'member_id' => '5088', 'matchday_id' => '577'],
            ['id' => '165907', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '12', 'member_id' => '5079', 'matchday_id' => '576'],
            ['id' => '166470', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '0', 'regular' => '0', 'quotation' => '12', 'member_id' => '5079', 'matchday_id' => '577'],
            ['id' => '165886', 'valued' => '0', 'points' => '0', 'rating' => '0', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '0', 'penalities_taken' => '0', 'present' => '1', 'regular' => '0', 'quotation' => '18', 'member_id' => '5055', 'matchday_id' => '576'],
            ['id' => '166449', 'valued' => '1', 'points' => '1.5', 'rating' => '4.5', 'goals' => '0', 'goals_against' => '0', 'goals_victory' => '0', 'goals_tie' => '0', 'assist' => '0', 'yellow_card' => '0', 'red_card' => '0', 'penalities_scored' => '1', 'penalities_taken' => '0', 'present' => '1', 'regular' => '1', 'quotation' => '16', 'member_id' => '5055', 'matchday_id' => '577'],
        ];
        parent::init();
    }
}
