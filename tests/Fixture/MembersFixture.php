<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MembersFixture
 *
 */
class MembersFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'code_gazzetta' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'active' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'playmaker' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created_at' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => '', 'precision' => null],
        'modified_at' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'player_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'role_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'club_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'season_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'player_id' => ['type' => 'index', 'columns' => ['player_id'], 'length' => []],
            'role_id' => ['type' => 'index', 'columns' => ['role_id'], 'length' => []],
            'club_id' => ['type' => 'index', 'columns' => ['club_id'], 'length' => []],
            'season_id' => ['type' => 'index', 'columns' => ['season_id'], 'length' => []],
            'active' => ['type' => 'index', 'columns' => ['active'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'codice_gazzetta' => ['type' => 'unique', 'columns' => ['code_gazzetta', 'season_id'], 'length' => []],
            'members_ibfk_1' => ['type' => 'foreign', 'columns' => ['player_id'], 'references' => ['players', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'members_ibfk_2' => ['type' => 'foreign', 'columns' => ['role_id'], 'references' => ['roles', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'members_ibfk_3' => ['type' => 'foreign', 'columns' => ['club_id'], 'references' => ['clubs', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'members_ibfk_4' => ['type' => 'foreign', 'columns' => ['season_id'], 'references' => ['seasons', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
        ['id' => '4656', 'code_gazzetta' => '157', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:42', 'modified_at' => null, 'player_id' => '1384', 'role_id' => '1', 'club_id' => '10', 'season_id' => '16'],
        ['id' => '4635', 'code_gazzetta' => '135', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:42', 'modified_at' => null, 'player_id' => '658', 'role_id' => '1', 'club_id' => '10', 'season_id' => '16'],
        ['id' => '4653', 'code_gazzetta' => '154', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:42', 'modified_at' => null, 'player_id' => '45', 'role_id' => '1', 'club_id' => '6', 'season_id' => '16'],
        ['id' => '4754', 'code_gazzetta' => '294', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1857', 'role_id' => '2', 'club_id' => '18', 'season_id' => '16'],
        ['id' => '4700', 'code_gazzetta' => '234', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1603', 'role_id' => '2', 'club_id' => '13', 'season_id' => '16'],
        ['id' => '4686', 'code_gazzetta' => '219', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:42', 'modified_at' => null, 'player_id' => '1600', 'role_id' => '2', 'club_id' => '10', 'season_id' => '16'],
        ['id' => '4775', 'code_gazzetta' => '319', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '180', 'role_id' => '2', 'club_id' => '1', 'season_id' => '16'],
        ['id' => '4798', 'code_gazzetta' => '342', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '207', 'role_id' => '2', 'club_id' => '11', 'season_id' => '16'],
        ['id' => '4762', 'code_gazzetta' => '305', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1628', 'role_id' => '2', 'club_id' => '25', 'season_id' => '16'],
        ['id' => '4750', 'code_gazzetta' => '290', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1193', 'role_id' => '2', 'club_id' => '22', 'season_id' => '16'],
        ['id' => '4777', 'code_gazzetta' => '321', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1838', 'role_id' => '2', 'club_id' => '7', 'season_id' => '16'],
        ['id' => '4993', 'code_gazzetta' => '644', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1709', 'role_id' => '3', 'club_id' => '23', 'season_id' => '16'],
        ['id' => '5028', 'code_gazzetta' => '686', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1277', 'role_id' => '3', 'club_id' => '14', 'season_id' => '16'],
        ['id' => '4903', 'code_gazzetta' => '542', 'active' => '1', 'playmaker' => '1', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1736', 'role_id' => '3', 'club_id' => '7', 'season_id' => '16'],
        ['id' => '5051', 'code_gazzetta' => '710', 'active' => '1', 'playmaker' => '1', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '2152', 'role_id' => '3', 'club_id' => '1', 'season_id' => '16'],
        ['id' => '4913', 'code_gazzetta' => '553', 'active' => '1', 'playmaker' => '1', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1669', 'role_id' => '3', 'club_id' => '20', 'season_id' => '16'],
        ['id' => '4945', 'code_gazzetta' => '593', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1676', 'role_id' => '3', 'club_id' => '23', 'season_id' => '16'],
        ['id' => '5005', 'code_gazzetta' => '658', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1979', 'role_id' => '3', 'club_id' => '8', 'season_id' => '16'],
        ['id' => '4958', 'code_gazzetta' => '608', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1896', 'role_id' => '3', 'club_id' => '11', 'season_id' => '16'],
        ['id' => '5084', 'code_gazzetta' => '834', 'active' => '1', 'playmaker' => '1', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '859', 'role_id' => '4', 'club_id' => '10', 'season_id' => '16'],
        ['id' => '5123', 'code_gazzetta' => '883', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1766', 'role_id' => '4', 'club_id' => '14', 'season_id' => '16'],
        ['id' => '5176', 'code_gazzetta' => '942', 'active' => '1', 'playmaker' => '1', 'created_at' => '2018-08-21 16:04:44', 'modified_at' => null, 'player_id' => '2184', 'role_id' => '4', 'club_id' => '17', 'season_id' => '16'],
        ['id' => '5088', 'code_gazzetta' => '839', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1539', 'role_id' => '4', 'club_id' => '2', 'season_id' => '16'],
        ['id' => '5079', 'code_gazzetta' => '829', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '538', 'role_id' => '4', 'club_id' => '2', 'season_id' => '16'],
        ['id' => '5055', 'code_gazzetta' => '801', 'active' => '1', 'playmaker' => '0', 'created_at' => '2018-08-21 16:04:43', 'modified_at' => null, 'player_id' => '1941', 'role_id' => '4', 'club_id' => '32', 'season_id' => '16']
        ];
        parent::init();
    }
}
