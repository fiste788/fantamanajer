<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TransfertsFixture
 *
 */
class TransfertsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'old_member_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'new_member_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'team_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'matchday_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'constrained' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'IdGiocOld' => ['type' => 'index', 'columns' => ['old_member_id'], 'length' => []],
            'IdGiocNew' => ['type' => 'index', 'columns' => ['new_member_id'], 'length' => []],
            'idSquadra' => ['type' => 'index', 'columns' => ['team_id'], 'length' => []],
            'idGiornata' => ['type' => 'index', 'columns' => ['matchday_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'transferts_ibfk_1' => ['type' => 'foreign', 'columns' => ['old_member_id'], 'references' => ['members', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'transferts_ibfk_2' => ['type' => 'foreign', 'columns' => ['new_member_id'], 'references' => ['members', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'transferts_ibfk_3' => ['type' => 'foreign', 'columns' => ['team_id'], 'references' => ['teams', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'transferts_ibfk_4' => ['type' => 'foreign', 'columns' => ['matchday_id'], 'references' => ['matchdays', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
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
            'id' => 1,
            'old_member_id' => 1,
            'new_member_id' => 1,
            'team_id' => 1,
            'matchday_id' => 1,
            'constrained' => 1
        ],
    ];
}
