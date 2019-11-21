<?php

declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MembersTeamsFixture
 */
class MembersTeamsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'team_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'member_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'team_id' => ['type' => 'index', 'columns' => ['team_id'], 'length' => []],
            'member_id' => ['type' => 'index', 'columns' => ['member_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'members_teams_ibfk_2' => ['type' => 'foreign', 'columns' => ['member_id'], 'references' => ['members', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'members_teams_ibfk_3' => ['type' => 'foreign', 'columns' => ['team_id'], 'references' => ['teams', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
            ['id' => '1185', 'team_id' => '55', 'member_id' => '4656'],
            ['id' => '1186', 'team_id' => '55', 'member_id' => '4635'],
            ['id' => '1187', 'team_id' => '55', 'member_id' => '4653'],
            ['id' => '1188', 'team_id' => '55', 'member_id' => '4754'],
            ['id' => '1189', 'team_id' => '55', 'member_id' => '4700'],
            ['id' => '1190', 'team_id' => '55', 'member_id' => '4686'],
            ['id' => '1191', 'team_id' => '55', 'member_id' => '4775'],
            ['id' => '1192', 'team_id' => '55', 'member_id' => '4798'],
            ['id' => '1193', 'team_id' => '55', 'member_id' => '4762'],
            ['id' => '1194', 'team_id' => '55', 'member_id' => '4750'],
            ['id' => '1195', 'team_id' => '55', 'member_id' => '4777'],
            ['id' => '1196', 'team_id' => '55', 'member_id' => '4993'],
            ['id' => '1197', 'team_id' => '55', 'member_id' => '5028'],
            ['id' => '1198', 'team_id' => '55', 'member_id' => '4903'],
            ['id' => '1199', 'team_id' => '55', 'member_id' => '5051'],
            ['id' => '1200', 'team_id' => '55', 'member_id' => '4913'],
            ['id' => '1201', 'team_id' => '55', 'member_id' => '4945'],
            ['id' => '1202', 'team_id' => '55', 'member_id' => '5005'],
            ['id' => '1203', 'team_id' => '55', 'member_id' => '4958'],
            ['id' => '1204', 'team_id' => '55', 'member_id' => '5084'],
            ['id' => '1205', 'team_id' => '55', 'member_id' => '5123'],
            ['id' => '1206', 'team_id' => '55', 'member_id' => '5176'],
            ['id' => '1207', 'team_id' => '55', 'member_id' => '5088'],
            ['id' => '1208', 'team_id' => '55', 'member_id' => '5079'],
            ['id' => '1209', 'team_id' => '55', 'member_id' => '5055'],
        ];
        parent::init();
    }
}
