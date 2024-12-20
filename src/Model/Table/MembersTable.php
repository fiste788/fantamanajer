<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Member;
use ArrayObject;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Members Model
 *
 * @property \App\Model\Table\PlayersTable&\Cake\ORM\Association\BelongsTo $Players
 * @property \App\Model\Table\RolesTable&\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\ClubsTable&\Cake\ORM\Association\BelongsTo $Clubs
 * @property \App\Model\Table\SeasonsTable&\Cake\ORM\Association\BelongsTo $Seasons
 * @property \App\Model\Table\DispositionsTable&\Cake\ORM\Association\HasMany $Dispositions
 * @property \App\Model\Table\RatingsTable&\Cake\ORM\Association\HasMany $Ratings
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\BelongsToMany $Teams
 * @property \App\Model\Table\MembersStatsTable&\Cake\ORM\Association\HasOne $MembersStats
 * @method \App\Model\Entity\Member get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Member newEntity(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Member[] newEntities(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Member|false save(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Member saveOrFail(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Member patchEntity(\Cake\Datasource\EntityInterface $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Member[] patchEntities(iterable<\Cake\Datasource\EntityInterface> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Member findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Member newEmptyEntity()
 * @method \App\Model\Entity\Member[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Member>|false saveMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Member[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Member> saveManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Member[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Member>|false deleteMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Member[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Member> deleteManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 */
class MembersTable extends Table
{
    use LocatorAwareTrait;

    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('members');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Players', [
            'foreignKey' => 'player_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Clubs', [
            'foreignKey' => 'club_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Seasons', [
            'foreignKey' => 'season_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Dispositions', [
            'foreignKey' => 'member_id',
        ]);
        $this->hasMany('Ratings', [
            'foreignKey' => 'member_id',
            'strategy' => 'select',
        ]);
        $this->hasOne('MembersStats', [
            'foreignKey' => 'member_id',
            'propertyName' => 'stats',
        ]);
        $this->belongsToMany('Teams', [
            'foreignKey' => 'member_id',
            'targetForeignKey' => 'team_id',
            'joinTable' => 'members_teams',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     * @throws \InvalidArgumentException
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('code_gazzetta')
            ->requirePresence('code_gazzetta', 'create')
            ->notEmptyString('code_gazzetta');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        $validator
            ->boolean('playmaker')
            ->notEmptyString('playmaker');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('modified_at')
            ->allowEmptyDateTime('modified_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     * @throws \Cake\Core\Exception\CakeException If a rule with the same name already exists
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['player_id'], 'Players'));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));

        return $rules;
    }

    /**
     * Find by season id
     *
     * @param int $season_id Season id
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findListBySeasonId(int $season_id): SelectQuery
    {
        return $this->find(
            'list',
            keyField: 'code_gazzetta',
            valueField: function (Member $obj): Member {
                return $obj;
            }
        )->where(['season_id' => $season_id]);
    }

    /**
     * Find with stats query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findWithStats(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->contain(['MembersStats'])
            ->where(['season_id' => $args['season_id']])
            ->groupBy('Members.id');
    }

    /**
     * Find matchday ratings as list
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findListWithRating(SelectQuery $query, mixed ...$args): SelectQuery
    {
        /** @var int $matchdayId */
        $matchdayId = $args['matchday_id'];

        return $query->find(
            'list',
            keyField: 'id',
            valueField: function (Member $obj): Member {
                return $obj;
            }
        )
            ->contain(
                [
                    'Roles',
                    'Ratings' => function (SelectQuery $q) use ($matchdayId): SelectQuery {
                        return $q->where(['Ratings.matchday_id' => $matchdayId]);
                    },
                ]
            );
    }

    /**
     * Find with details
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findWithDetails(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $query->contain(
            [
                'Roles',
                'Clubs',
                'Seasons',
                'Ratings' => function (SelectQuery $q): SelectQuery {
                    return $q->contain(['Matchdays'])
                        ->orderBy(['Matchdays.number' => 'ASC']);
                },
            ]
        )->orderBy(['Seasons.year' => 'DESC']);
        if (isset($args['championship_id'])) {
            $query->select(['player_id', 'free' => $query->newExpr()->isNull('Teams.id')])
                ->setDefaultTypes(['free' => 'boolean'])
                ->enableAutoFields(true)
                ->leftJoinWith('Teams', function (SelectQuery $q) use ($args): SelectQuery {
                    return $q->where(['championship_id' => $args['championship_id']]);
                });
        }

        return $query;
    }

    /**
     * Find free query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     * @throws \RuntimeException
     */
    public function findFree(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $championshipId = (int)$args['championship_id'];
        $membersTeams = $this->getTableLocator()->get('MembersTeams');
        $ids = $membersTeams->find()
            ->select(['member_id'])
            ->innerJoinWith(
                'Teams',
                function (SelectQuery $q) use ($championshipId): SelectQuery {
                    return $q->where(['Teams.championship_id' => $championshipId]);
                }
            );

        $query->innerJoinWith('Seasons.Championships')
            ->contain(['Players', 'Clubs', 'Roles'])
            ->where([
                'Members.id NOT IN' => $ids,
                'Members.active' => true,
                'Championships.id' => $championshipId,
            ])
            ->orderByAsc('Players.surname')
            ->orderByAsc('Players.name');
        if (isset($args['stats']) && $args['stats']) {
            $query->contain(['MembersStats']);
        }
        if (isset($args['role'])) {
            $query->where(['role_id' => $args['role']]);
        } else {
            $query->select(['id', 'Players.name', 'Players.surname', 'role_id', 'club_id']);
        }

        return $query;
    }

    /**
     * Find by club id query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByClubId(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->contain(['Roles', 'Players', 'MembersStats'])
            ->innerJoinWith('Clubs', function (SelectQuery $q) use ($args): SelectQuery {
                return $q->where(['Clubs.id' => $args['club_id']]);
            })->orderBy(['role_id', 'Players.name'])
            ->where([
                'active' => true,
                'season_id' => $args['season_id'],
            ]);
    }

    /**
     * Find by team id query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByTeamId(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $query->contain(['Clubs', 'Players'])
            ->innerJoinWith('Teams', function (SelectQuery $q) use ($args): SelectQuery {
                return $q->where(['Teams.id' => $args['team_id']]);
            })->orderBy(['role_id', 'Players.name']);
        if (isset($args['stats']) && $args['stats']) {
            $query->contain(['Roles', 'MembersStats']);
        }

        return $query;
    }

    /**
     * Find not mine query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function findNotMine(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $team = $this->Teams->get($args['team_id'], ['contain' => ['Championships']]);

        return $query->contain(['Players', 'Teams'])->leftJoinWith('Teams')->where([
            'Members.role_id' => $args['role_id'],
            'OR' => [
                'Teams.id !=' => $args['team_id'],
                'Teams.id IS' => null,
            ],
            'Members.season_id' => $team->championship->season_id,
        ]);
    }

    /**
     * Find best by matchday and role
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findBestByMatchdayIdAndRole(SelectQuery $query, mixed ...$args): SelectQuery
    {
        /** @var \App\Model\Entity\Role $role */
        $role = $args['role'];

        return $query->contain([
            'Players',
            'Ratings' => function (SelectQuery $q) use ($args): SelectQuery {
                return $q->select(['member_id', 'points'])
                    ->where(['matchday_id' => $args['matchday_id']]);
            },
        ])->innerJoinWith('Ratings', function (SelectQuery $q) use ($args): SelectQuery {
            return $q->where(['matchday_id' => $args['matchday_id']]);
        })->innerJoinWith('Roles')
            ->where(['Roles.id' => $role->id])
            ->orderByDesc('Ratings.points');
    }

    /**
     * Find best by matchday and role
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed $args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findBestByMatchdayId(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $expr = $query->newExpr('RANK() OVER(PARTITION BY role_id ORDER BY points DESC, surname ASC)');
        $contentQuery = $this->find()
            ->select(['Members.id', 'role_id', 'Ratings.points', 'rank' => $expr])
            ->innerJoinWith('Ratings')->innerJoinWith('Players')
            ->where(['matchday_id' => $args['matchday_id']]);

        return $query->contain([
            'Players',
            'Ratings' => function (SelectQuery $q1) use ($args): SelectQuery {
                return $q1->select(['member_id', 'points'])
                    ->where(['matchday_id' => $args['matchday_id']]);
            },
        ])
            ->join([
                't' => [
                    'table' => $contentQuery,
                    'type' => 'LEFT',
                    'conditions' => [
                        't.Members__id' => new IdentifierExpression('Members.id'),
                    ],
                ],
            ])
            ->where(['t.rank <=' => 5]);
    }

    /**
     * After save event
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\Member $entity Entity
     * @param \ArrayObject $options Options
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options): void
    {
        $ev = new Event('Fantamanajer.changeMember', $this, [
            'member' => $entity,
        ]);
        EventManager::instance()->dispatch($ev);
    }
}
