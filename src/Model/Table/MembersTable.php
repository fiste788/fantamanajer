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
use Cake\ORM\Query;
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
 * @method \App\Model\Entity\Member get($primaryKey, $options = [])
 * @method \App\Model\Entity\Member newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Member[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Member|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Member saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Member patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Member[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Member findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Member newEmptyEntity()
 * @method \App\Model\Entity\Member[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Member[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Member[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Member[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
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
     * @return \Cake\ORM\Query
     */
    public function findListBySeasonId(int $season_id): Query
    {
        return $this->find('list', [
            'keyField' => 'code_gazzetta',
            'valueField' => function (Member $obj): Member {
                return $obj;
            },
        ])->where(['season_id' => $season_id]);
    }

    /**
     * Find with stats query
     *
     * @param \Cake\ORM\Query $query Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findWithStats(Query $query, array $options): Query
    {
        return $query->contain(['MembersStats'])
            ->where(['season_id' => $options['season_id']])
            ->group('Members.id');
    }

    /**
     * Find matchday ratings as list
     *
     * @param \Cake\ORM\Query $query Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findListWithRating(Query $query, array $options): Query
    {
        /** @var int $matchdayId */
        $matchdayId = $options['matchday_id'];

        return $query->find('list', [
                'keyField' => 'id',
                'valueField' => function (Member $obj): Member {
                    return $obj;
                },
            ])
            ->contain(
                ['Roles', 'Ratings' => function (Query $q) use ($matchdayId): Query {
                    return $q->where(['Ratings.matchday_id' => $matchdayId]);
                }]
            );
    }

    /**
     * Find with details
     *
     * @param \Cake\ORM\Query $query Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findWithDetails(Query $query, array $options): Query
    {
        $query->contain(
            ['Roles', 'Clubs', 'Seasons', 'Ratings' => function (Query $q): Query {
                return $q->contain(['Matchdays'])
                    ->order(['Matchdays.number' => 'ASC']);
            }]
        )->order(['Seasons.year' => 'DESC']);
        if (isset($options['championship_id'])) {
            $query->select(['player_id', 'free' => $query->newExpr()->isNull('Teams.id')])
                ->setDefaultTypes(['free' => 'boolean'])
                ->enableAutoFields(true)
                ->leftJoinWith('Teams', function (Query $q) use ($options): Query {
                    return $q->where(['championship_id' => $options['championship_id']]);
                });
        }

        return $query;
    }

    /**
     * Find free query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     * @throws \RuntimeException
     */
    public function findFree(Query $q, array $options): Query
    {
        $championshipId = (int)$options['championship_id'];
        $membersTeams = $this->getTableLocator()->get('MembersTeams');
        $ids = $membersTeams->find()
            ->select(['member_id'])
            ->innerJoinWith(
                'Teams',
                function (Query $q) use ($championshipId): Query {
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
            $q->contain(['MembersStats']);
        }
        if (isset($options['role'])) {
            $q->where(['role_id' => $options['role']]);
        } else {
            $q->select(['id', 'Players.name', 'Players.surname', 'role_id', 'club_id']);
        }

        return $q;
    }

    /**
     * Find by club id query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findByClubId(Query $q, array $options): Query
    {
        return $q->contain(['Roles', 'Players', 'MembersStats'])
            ->innerJoinWith('Clubs', function (Query $q) use ($options): Query {
                return $q->where(['Clubs.id' => $options['club_id']]);
            })->order(['role_id', 'Players.name'])
            ->where([
                'active' => true,
                'season_id' => $options['season_id'],
            ]);
    }

    /**
     * Find by team id query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findByTeamId(Query $q, array $options): Query
    {
        $q->contain(['Clubs', 'Players'])
            ->innerJoinWith('Teams', function (Query $q) use ($options): Query {
                return $q->where(['Teams.id' => $options['team_id']]);
            })->order(['role_id', 'Players.name']);
        if (isset($options['stats']) && $options['stats']) {
            $q->contain(['Roles', 'MembersStats']);
        }

        return $q;
    }

    /**
     * Find not mine query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
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

    /**
     * Find best by matchday and role
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findBestByMatchdayIdAndRole(Query $q, array $options): Query
    {
        /** @var \App\Model\Entity\Role $role */
        $role = $options['role'];

        return $q->contain([
            'Players', 'Ratings' => function (Query $q) use ($options): Query {
                return $q->select(['member_id', 'points'])
                    ->where(['matchday_id' => $options['matchday_id']]);
            },
        ])->innerJoinWith('Ratings', function (Query $q) use ($options): Query {
            return $q->where(['matchday_id' => $options['matchday_id']]);
        })->innerJoinWith('Roles')
            ->where(['Roles.id' => $role->id])
            ->orderDesc('Ratings.points');
    }

    /**
     * Find best by matchday and role
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findBestByMatchdayId(Query $q, array $options): Query
    {
        $expr = $q->newExpr('ROW_NUMBER() OVER(PARTITION BY role_id ORDER BY points DESC)');
        $contentQuery = $this->find()
            ->select(['Members.id', 'role_id', 'Ratings.points', 'row_number' => $expr])
            ->innerJoinWith('Ratings')
            ->where(['matchday_id' => $options['matchday_id']]);

        return $q->contain([
            'Players', 'Ratings' => function (Query $q1) use ($options): Query {
                return $q1->select(['member_id', 'points'])
                    ->where(['matchday_id' => $options['matchday_id']]);
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
            ->where(['t.row_number <' => 5]);
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
