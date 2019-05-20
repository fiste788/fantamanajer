<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DispositionsFixture
 *
 */
class DispositionsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'position' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'consideration' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'lineup_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'member_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'lineup_id' => ['type' => 'index', 'columns' => ['lineup_id'], 'length' => []],
            'member_id' => ['type' => 'index', 'columns' => ['member_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'dispositions_ibfk_1' => ['type' => 'foreign', 'columns' => ['lineup_id'], 'references' => ['lineups', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'dispositions_ibfk_2' => ['type' => 'foreign', 'columns' => ['member_id'], 'references' => ['members', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
        $this->records = array(
  array('id' => '37811','position' => '1','consideration' => '1','lineup_id' => '1781','member_id' => '4656'),
  array('id' => '37812','position' => '2','consideration' => '2','lineup_id' => '1781','member_id' => '4762'),
  array('id' => '37813','position' => '3','consideration' => '0','lineup_id' => '1781','member_id' => '4754'),
  array('id' => '37814','position' => '4','consideration' => '1','lineup_id' => '1781','member_id' => '4777'),
  array('id' => '37815','position' => '5','consideration' => '1','lineup_id' => '1781','member_id' => '4903'),
  array('id' => '37816','position' => '6','consideration' => '1','lineup_id' => '1781','member_id' => '5028'),
  array('id' => '37817','position' => '7','consideration' => '1','lineup_id' => '1781','member_id' => '4993'),
  array('id' => '37818','position' => '8','consideration' => '1','lineup_id' => '1781','member_id' => '4913'),
  array('id' => '37819','position' => '9','consideration' => '1','lineup_id' => '1781','member_id' => '5055'),
  array('id' => '37820','position' => '10','consideration' => '1','lineup_id' => '1781','member_id' => '5084'),
  array('id' => '37821','position' => '11','consideration' => '0','lineup_id' => '1781','member_id' => '5176'),
  array('id' => '37822','position' => '12','consideration' => '0','lineup_id' => '1781','member_id' => '4635'),
  array('id' => '37823','position' => '13','consideration' => '1','lineup_id' => '1781','member_id' => '4750'),
  array('id' => '37824','position' => '14','consideration' => '0','lineup_id' => '1781','member_id' => '4798'),
  array('id' => '37825','position' => '15','consideration' => '0','lineup_id' => '1781','member_id' => '5051'),
  array('id' => '37826','position' => '16','consideration' => '0','lineup_id' => '1781','member_id' => '4958'),
  array('id' => '37827','position' => '17','consideration' => '0','lineup_id' => '1781','member_id' => '5123'),
  array('id' => '37828','position' => '18','consideration' => '1','lineup_id' => '1781','member_id' => '5088')
);
        parent::init();
    }
}
