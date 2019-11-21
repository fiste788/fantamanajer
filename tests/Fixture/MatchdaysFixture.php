<?php

declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MatchdaysFixture
 */
class MatchdaysFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'number' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'date' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => false, 'default' => null, 'comment' => ''],
        'season_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'season_id' => ['type' => 'index', 'columns' => ['season_id'], 'length' => []],
            'number' => ['type' => 'index', 'columns' => ['number'], 'length' => []],
            'date' => ['type' => 'index', 'columns' => ['date'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'matchdays_ibfk_1' => ['type' => 'foreign', 'columns' => ['season_id'], 'references' => ['seasons', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
            ['id' => '569', 'number' => '0', 'date' => '2018-08-10 00:00:00', 'season_id' => '16'],
            ['id' => '570', 'number' => '1', 'date' => '2018-08-18 18:00:00', 'season_id' => '16'],
            ['id' => '571', 'number' => '2', 'date' => '2018-08-25 18:00:00', 'season_id' => '16'],
            ['id' => '572', 'number' => '3', 'date' => '2018-08-31 20:30:00', 'season_id' => '16'],
            ['id' => '573', 'number' => '4', 'date' => '2018-09-15 15:00:00', 'season_id' => '16'],
            ['id' => '574', 'number' => '5', 'date' => '2018-09-21 20:30:00', 'season_id' => '16'],
            ['id' => '575', 'number' => '6', 'date' => '2018-09-25 21:00:00', 'season_id' => '16'],
            ['id' => '576', 'number' => '7', 'date' => '2018-09-29 15:00:00', 'season_id' => '16'],
            ['id' => '577', 'number' => '8', 'date' => '2018-10-05 20:30:00', 'season_id' => '16'],
            ['id' => '578', 'number' => '9', 'date' => '2018-10-05 20:30:00', 'season_id' => '16'],
            ['id' => '579', 'number' => '10', 'date' => '2018-10-05 20:30:00', 'season_id' => '16'],
            ['id' => '580', 'number' => '11', 'date' => '2018-11-02 20:30:00', 'season_id' => '16'],
            ['id' => '581', 'number' => '12', 'date' => '2018-11-11 18:00:00', 'season_id' => '16'],
            ['id' => '582', 'number' => '13', 'date' => '2018-11-25 18:00:00', 'season_id' => '16'],
            ['id' => '583', 'number' => '14', 'date' => '2018-12-02 18:00:00', 'season_id' => '16'],
            ['id' => '584', 'number' => '15', 'date' => '2018-12-07 20:30:00', 'season_id' => '16'],
            ['id' => '585', 'number' => '16', 'date' => '2018-12-15 20:30:00', 'season_id' => '16'],
            ['id' => '586', 'number' => '17', 'date' => '2018-12-22 18:00:00', 'season_id' => '16'],
            ['id' => '587', 'number' => '18', 'date' => '2018-12-26 18:00:00', 'season_id' => '16'],
            ['id' => '588', 'number' => '19', 'date' => '2018-12-29 18:00:00', 'season_id' => '16'],
            ['id' => '589', 'number' => '20', 'date' => '2019-01-20 18:00:00', 'season_id' => '16'],
            ['id' => '590', 'number' => '21', 'date' => '2019-01-26 20:30:00', 'season_id' => '16'],
            ['id' => '591', 'number' => '22', 'date' => '2019-02-03 18:00:00', 'season_id' => '16'],
            ['id' => '592', 'number' => '23', 'date' => '2019-02-10 18:00:00', 'season_id' => '16'],
            ['id' => '593', 'number' => '24', 'date' => '2019-02-17 18:00:00', 'season_id' => '16'],
            ['id' => '594', 'number' => '25', 'date' => '2019-02-24 18:00:00', 'season_id' => '16'],
            ['id' => '595', 'number' => '26', 'date' => '2019-03-03 18:00:00', 'season_id' => '16'],
            ['id' => '596', 'number' => '27', 'date' => '2019-03-10 18:00:00', 'season_id' => '16'],
            ['id' => '597', 'number' => '28', 'date' => '2019-03-17 18:00:00', 'season_id' => '16'],
            ['id' => '598', 'number' => '29', 'date' => '2019-03-31 18:00:00', 'season_id' => '16'],
            ['id' => '599', 'number' => '30', 'date' => '2019-04-03 18:00:00', 'season_id' => '16'],
            ['id' => '600', 'number' => '31', 'date' => '2019-04-07 18:00:00', 'season_id' => '16'],
            ['id' => '601', 'number' => '32', 'date' => '2019-04-14 18:00:00', 'season_id' => '16'],
            ['id' => '602', 'number' => '33', 'date' => '2019-04-20 18:00:00', 'season_id' => '16'],
            ['id' => '603', 'number' => '34', 'date' => '2019-04-27 20:30:00', 'season_id' => '16'],
            ['id' => '604', 'number' => '35', 'date' => '2019-05-05 18:00:00', 'season_id' => '16'],
            ['id' => '605', 'number' => '36', 'date' => '2019-05-12 18:00:00', 'season_id' => '16'],
            ['id' => '606', 'number' => '37', 'date' => '2019-05-19 18:00:00', 'season_id' => '16'],
            ['id' => '607', 'number' => '38', 'date' => '2019-05-26 18:00:00', 'season_id' => '16'],
            ['id' => '608', 'number' => '39', 'date' => '2019-07-31 23:59:59', 'season_id' => '16'],
        ];
        parent::init();
    }
}
