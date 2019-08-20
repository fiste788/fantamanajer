<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Members Model
 *
 * @property \App\Model\Table\PlayersTable|\Cake\ORM\Association\BelongsTo $Players
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\ClubsTable|\Cake\ORM\Association\BelongsTo $Clubs
 * @property \App\Model\Table\SeasonsTable|\Cake\ORM\Association\BelongsTo $Seasons
 * @property \App\Model\Table\DispositionsTable|\Cake\ORM\Association\HasMany $Dispositions
 * @property \App\Model\Table\RatingsTable|\Cake\ORM\Association\HasMany $Ratings
 * @property \App\Model\Table\TeamsTable|\Cake\ORM\Association\BelongsToMany $Teams
 *
 * @method \App\Model\Entity\Member get($primaryKey, $options = [])
 * @method \App\Model\Entity\Member newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Member[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Member|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Member patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Member[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Member findOrCreate($search, callable $callback = null, $options = [])
 * @property \App\Model\Table\VwMembersStatsTable|\Cake\ORM\Association\HasOne $VwMembersStats
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Member|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class MembersTable extends Table
{
    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('members');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Players',
            [
                'foreignKey' => 'player_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Roles',
            [
                'foreignKey' => 'role_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Clubs',
            [
                'foreignKey' => 'club_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Seasons',
            [
                'foreignKey' => 'season_id',
                'joinType' => 'INNER',
            ]
        );
        $this->hasMany(
            'Dispositions',
            [
                'foreignKey' => 'member_id',
            ]
        );
        $this->hasMany(
            'Ratings',
            [
                'foreignKey' => 'member_id',
                'strategy' => 'select',
            ]
        );
        $this->belongsToMany(
            'Teams',
            [
                'foreignKey' => 'member_id',
                'targetForeignKey' => 'team_id',
                'joinTable' => 'members_teams',
            ]
        );
        $this->hasOne(
            'VwMembersStats',
            [
                'foreignKey' => 'member_id',
                'propertyName' => 'stats',
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('code_gazzetta')
            ->requirePresence('code_gazzetta', 'create')
            ->notEmpty('code_gazzetta');

        $validator
            ->boolean('playmaker');

        $validator
            ->boolean('active');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['player_id'], 'Players'));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));

        return $rules;
    }

    public function findListBySeasonId($season_id)
    {
        return $this->find('list', [
            'keyField' => 'code_gazzetta',
            'valueField' => function ($obj) {
                return $obj;
            },
        ])->where(['season_id' => $season_id]);
    }

    public function findWithStats(Query $query, array $options): Query
    {
        return $query->contain(['VwMembersStats'])
            ->where(['season_id' => $options['season_id']])
            ->group('Members.id');
    }

    public function findWithDetails(Query $query, array $options): Query
    {
        $query->contain(
            ['Roles', 'Clubs', 'Seasons', 'Ratings' => function (Query $q) {
                return $q->contain(['Matchdays'])
                    ->order(['Matchdays.number' => 'ASC']);
            }]
        )->order(['Seasons.year' => 'DESC']);
        if ($options['championship_id']) {
            $query->select(['player_id', 'free' => $query->newExpr()->isNull('Teams.id')])
                ->setDefaultTypes(['free' => 'boolean'])
                ->enableAutoFields(true)
                ->leftJoinWith('Teams', function (Query $q) use ($options) {
                    return $q->where(['championship_id' => $options['championship_id']]);
                });
        }

        return $query;
    }

    public function findFree(Query $q, array $options): Query
    {
        $championshipId = $options['championship_id'];
        $membersTeams = TableRegistry::get('MembersTeams');
        $ids = $membersTeams->find()
            ->select(['member_id'])
            ->innerJoinWith(
                'Teams',
                function ($q) use ($championshipId) {
                    return $q->where(['Teams.championship_id' => $championshipId]);
                }
            );

        $q->innerJoinWith('Seasons.Championships')
            ->contain(['Players', 'Clubs', 'Roles'])
            ->where([
                'Members.id NOT IN' => $ids,
                'Members.active' => true,
                'Championships.id' => $championshipId,
            ])
            ->orderAsc('Players.surname')
            ->orderAsc('Players.name');
        if (isset($options['stats']) && $options['stats']) {
            $q->contain(['VwMembersStats']);
        }
        if (isset($options['role'])) {
            $q->where(['role_id' => $options['role']]);
        } else {
            $q->select(['id', 'Players.name', 'Players.surname', 'role_id']);
        }

        return $q;
    }

    public function findByClubId(Query $q, array $options): Query
    {
        return $q->contain(['Roles', 'Players', 'VwMembersStats'])
            ->innerJoinWith('Clubs', function (Query $q) use ($options) {
                return $q->where(['Clubs.id' => $options['club_id']]);
            })->order(['role_id', 'Players.name'])
            ->where([
                'active' => true,
                'season_id' => $options['season_id'],
            ]);
    }

    public function findByTeamId(Query $q, array $options): Query
    {
        $q->contain(['Clubs', 'Players'])
            ->innerJoinWith('Teams', function (Query $q) use ($options) {
                return $q->where(['Teams.id' => $options['team_id']]);
            })->order(['role_id', 'Players.name']);
        if ($options['stats']) {
            $q->contain(['Roles', 'VwMembersStats']);
        }

        return $q;
    }

    public function findNotMine(Query $q, array $options): Query
    {
        $team = $this->Teams->get($options['team_id'], ['contain' => ['Championships']]);
        $q->contain(['Players', 'Teams'])->leftJoinWith('Teams')->where([
            'Members.role_id' => $options['role_id'],
            'OR' => [
                'Teams.id !=' => $options['team_id'],
                'Teams.id IS' => null,
            ],
            'Members.season_id' => $team->championship->season_id,
        ]);

        return $q;
    }

    public function findBestByMatchdayIdAndRole(Query $q, array $options): Query
    {
        return $q->contain([
            'Players', 'Ratings' => function (Query $q) use ($options) {
                return $q->where(['matchday_id' => $options['matchday_id']]);
            },
        ])->innerJoinWith('Ratings', function (Query $q) use ($options) {
            return $q->where(['matchday_id' => $options['matchday_id']]);
        })->innerJoinWith('Roles')
            ->where(['Roles.id' => $options['role']->id])
            ->orderDesc('Ratings.points');
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options): void
    {
        $ev = new Event('Fantamanajer.changeMember', $this, [
            'member' => $entity,
        ]);
        EventManager::instance()->dispatch($ev);
    }
}
