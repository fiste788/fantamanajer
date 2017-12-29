<?php
namespace App\Model\Table;

use App\Model\Entity\Event as Event2;
use App\Model\Entity\Matchday;
use App\Model\Entity\Role;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Members Model
 *
 * @property BelongsTo $Players
 * @property BelongsTo $Roles
 * @property BelongsTo $Clubs
 * @property BelongsTo $Seasons
 * @property HasMany $Dispositions
 * @property HasMany $Ratings
 * @property HasMany $View0LineupsDetails
 * @property HasMany $VwMembersStats
 * @property BelongsToMany $Teams
 */
class MembersTable extends Table
{

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('members');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Players',
            [
            'foreignKey' => 'player_id',
            'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Roles',
            [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Clubs',
            [
            'foreignKey' => 'club_id',
            'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Seasons',
            [
            'foreignKey' => 'season_id',
            'joinType' => 'INNER'
            ]
        );
        $this->hasMany(
            'Dispositions',
            [
            'foreignKey' => 'member_id'
            ]
        );
        $this->hasMany(
            'Ratings',
            [
            'foreignKey' => 'member_id',
            'strategy' => 'select'
            ]
        );
        $this->belongsToMany(
            'Teams',
            [
            'foreignKey' => 'member_id',
            'targetForeignKey' => 'team_id',
            'joinTable' => 'members_teams'
            ]
        );
        $this->hasOne(
            'VwMembersStats',
            [
            'foreignKey' => 'member_id',
            'propertyName' => 'stats'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
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
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['player_id'], 'Players'));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
        $rules->add($rules->existsIn(['club_id'], 'Clubs'));
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));

        return $rules;
    }

    public function findWithStats(Query $query, array $options)
    {
        /*return $query
                ->select($this)
                ->select(['sum_present' => $query->func()->count('Ratings.present')])
                ->contain(['Ratings'])
                ->group('Members.id');*/
        return $query->hydrate(false)
            ->enableAutoFields(true)
            ->setDefaultTypes(
                [
                    'stats.sum_present' => 'decimal',
                    'stats.sum_valued' => 'decimal',
                    'stats.avg_points' => 'decimal',
                    'stats.avg_rating' => 'decimal',
                    'stats.sum_goals' => 'decimal',
                    'stats.sum_goals_against' => 'decimal',
                    'stats.sum_assist' => 'decimal',
                    'stats.sum_yellow_card' => 'decimal',
                    'stats.sum_red_card' => 'decimal'
                    ]
            )
            ->select(
                [
                    'Members.club_id',
                    'stats.sum_present',
                    'stats.sum_valued',
                    'stats.avg_points',
                    'stats.avg_rating',
                    'stats.sum_goals',
                    'stats.sum_goals_against',
                    'stats.sum_assist',
                    'stats.sum_yellow_card',
                    'stats.sum_red_card'
                    ]
            )
            ->join(
                [
                    'table' => 'vw_members_stats',
                    'alias' => 'stats',
                    'type' => 'LEFT',
                    'conditions' => 'stats.member_id = Members.id',
                    ]
            )
            ->where(['season_id' => $options['season_id']])
            ->group('Members.id');
    }

    public function findWithStats2(Query $query, array $options)
    {
        return $query->select(['sum_valued' => $query->func()->count('Ratings.valued')], false)
            ->enableAutoFields()
            ->innerJoinWith('Ratings')
            ->group('Members.id');
    }

    public function findFree($championshipId)
    {
        $membersTeams = TableRegistry::get('MembersTeams');
        $ids = $membersTeams->find()
            ->select(['member_id'])
            ->matching(
                'Teams',
                function ($q) use ($championshipId) {
                        return $q->where(['Teams.championship_id' => $championshipId]);
                }
            );

        return $this->find('all')
            ->contain(['Players', 'Clubs', 'Roles', 'VwMembersStats'])
                /*->where(function ($exp, $q) use ($ids) {
                    die(var_dump($ids));
                    return $exp->notIn('Members.id', $ids);
                })*/
            ->where(['Members.id NOT IN' => $ids])
            ->where(['Members.active' => true])
            ->orderAsc('Players.surname')
            ->orderAsc('Players.name');
    }

    public function findBestByMatchday(Matchday $matchday, Role $role, $limit = 5)
    {
        return $this->find('all')
            ->contain(
                ['Players', 'Ratings' => function (Query $q) use ($matchday) {
                        return $q->where(['matchday_id' => $matchday->id]);
                }]
            )
                ->innerJoinWith(
                    'Ratings',
                    function (Query $q) use ($matchday) {
                        return $q->where(['matchday_id' => $matchday->id]);
                    }
                )
                ->innerJoinWith('Roles')
                ->where(['Roles.id' => $role->id])
                ->orderDesc('Ratings.points')
                ->limit($limit);
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $events = TableRegistry::get('Events');
            $ev = $events->newEntity();
            $ev->type = Event2::NEW_PLAYER;
            $ev->team_id = $entity['team_id'];
            $ev->external = $entity['id'];
            $events->save($ev);
        } elseif ($entity->isDirty('club_id')) {
            $events = TableRegistry::get('Events');
            $ev = $events->newEntity();
            $ev->type = Event2::EDIT_CLUB;
            $ev->team_id = $entity['team_id'];
            $ev->external = $entity['id'];
            $events->save($ev);
        } elseif ($entity->isDirty('active')) {
            $events = TableRegistry::get('Events');
            $ev = $events->newEntity();
            $ev->type = Event2::DELETE_PLAYER;
            $ev->team_id = $entity['team_id'];
            $ev->external = $entity['id'];
            $events->save($ev);
        }
    }
}
