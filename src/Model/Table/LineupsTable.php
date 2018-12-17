<?php

namespace App\Model\Table;

use App\Model\Entity\Lineup;
use App\Model\Rule\JollyAlreadyUsedRule;
use App\Model\Rule\LineupExpiredRule;
use App\Model\Rule\MissingPlayerInLineupRule;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Lineups Model
 *
 * @property MembersTable|BelongsTo $Members
 * @property MembersTable|BelongsTo $Members
 * @property MembersTable|BelongsTo $Members
 * @property MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property DispositionsTable|\Cake\ORM\Association\HasMany $Dispositions
 * @property ScoresTable|\Cake\ORM\Association\HasOne $Scores
 * @property \App\Model\Table\View0LineupsDetailsTable|HasMany $View0LineupsDetails
 *
 * @method Lineup get($primaryKey, $options = [])
 * @method Lineup newEntity($data = null, array $options = [])
 * @method Lineup[] newEntities(array $data, array $options = [])
 * @method Lineup|bool save(EntityInterface $entity, $options = [])
 * @method Lineup patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Lineup[] patchEntities($entities, array $data, array $options = [])
 * @method Lineup findOrCreate($search, callable $callback = null, $options = [])
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $Captain
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $VCaptain
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $VVCaptain
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method Lineup|bool saveOrFail(EntityInterface $entity, $options = [])
 */
class LineupsTable extends Table
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

        $this->setTable('lineups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior(
            'Timestamp',
            [
                'events' => [
                    'Model.beforeSave' => [
                        'created_at' => 'new',
                        'modified_at' => 'always'
                    ]
                ]
            ]
        );

        $this->belongsTo(
            'Captain',
            [
                'className' => 'Members',
                'foreignKey' => 'captain_id'
            ]
        );
        $this->belongsTo(
            'VCaptain',
            [
                'className' => 'Members',
                'foreignKey' => 'vcaptain_id'
            ]
        );
        $this->belongsTo(
            'VVCaptain',
            [
                'className' => 'Members',
                'foreignKey' => 'vvcaptain_id'
            ]
        );
        $this->belongsTo(
            'Matchdays',
            [
                'foreignKey' => 'matchday_id',
                'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Teams',
            [
                'foreignKey' => 'team_id',
                'joinType' => 'INNER'
            ]
        );
        $this->hasMany(
            'Dispositions',
            [
                'foreignKey' => 'lineup_id',
                'sort' => ['Dispositions.position'],
                'saveStrategy' => 'replace'
            ]
        );
        $this->hasOne(
            'Scores',
            [
                'foreignKey' => 'lineup_id'
            ]
        );
        $this->hasMany(
            'View0LineupsDetails',
            [
                'foreignKey' => 'lineup_id'
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
            ->requirePresence('module', 'create')
            ->notEmpty('module');

        $validator
            ->boolean('jolly')
            ->allowEmpty('jolly');

        $validator
            ->boolean('cloned')
            ->allowEmpty('cloned');

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
        $rules->add($rules->existsIn(['captain_id'], 'Captain'));
        $rules->add($rules->existsIn(['vcaptain_id'], 'VCaptain'));
        $rules->add($rules->existsIn(['vvcaptain_id'], 'VVCaptain'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->isUnique(['team_id', 'matchday_id'], __('Lineup already exists for this matchday. Try to refresh')));
        $rules->addCreate(new LineupExpiredRule(), 'Expired',
            ['errorField' => 'module', 'message' => __('Expired lineup')]
        );
        $rules->add(new MissingPlayerInLineupRule(), 'MissingPlayer',
            ['errorField' => 'module', 'message' => __('Missing player/s')]
        );

        $rules->add(new JollyAlreadyUsedRule(), 'JollyAlreadyUsed',
            ['errorField' => 'jolly', 'message' => __('Jolly already used')]
        );

        return $rules;
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $event = new Event('Fantamanajer.newLineup', $this, [
                'lineup' => $entity
            ]);
            EventManager::instance()->dispatch($event);
        }
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (array_key_exists('created_at', $data)) {
            unset($data['created_at']);
        }
        if (array_key_exists('modified_at', $data)) {
            unset($data['modified_at']);
        }
    }

    public function findDetails(Query $q, array $options)
    {
        return $q->contain([
                'Teams',
                'Dispositions' => [
                    'Members' => [
                        'Roles', 'Players', 'Clubs', 'Ratings' => function ($q) use ($options) {
                            return $q->where(['matchday_id' => $options['matchday_id']]);
                        }]
                ]
            ])->where([
                'team_id' => $options['team_id'],
                'matchday_id' => $options['matchday_id']
        ]);
    }

    /**
     *
     * @param Query $q
     * @param array $options
     * @return Query
     */
    public function findLast(Query $q, array $options)
    {
        $q->innerJoinWith('Matchdays')
            ->contain(['Dispositions'])
            ->where([
                'Lineups.team_id' => $options['team_id'],
                'Lineups.matchday_id <=' => $options['matchday']->id,
                'Matchdays.season_id' => $options['matchday']->season_id
            ])
            ->order(['Matchdays.number' => 'DESC']);
        if (array_key_exists('stats', $options) && $options['stats']) {
            $seasonId = $options['matchday']->season_id;
            $tableLocator = TableRegistry::getTableLocator();
            $q->contain([
                'Teams' => [
                    'Members' => function(Query $q) use ($seasonId, $tableLocator) {
                        return $q->find('withStats', ['season_id' => $seasonId])
                                ->select($tableLocator->get('Roles'))
                                ->select($tableLocator->get('Players'))
                                ->select($tableLocator->get('VwMembersStats'))
                                ->select(['id', 'role_id'])
                                ->contain(['Roles', 'Players']);
                    }
                ]
            ]);
        }
        return $q;
    }

    public function findByMatchdayIdAndTeamId(Query $q, array $options)
    {
        return $q->contain(['Dispositions'])
                ->where([
                    'Lineups.team_id' => $options['team_id'],
                    'Lineups.matchday_id =' => $options['matchday_id'],
        ]);
    }

    public function findWithRatings(Query $q, array $options)
    {
        $matchdayId = $options['matchday_id'];

        return $q->contain([
                'Teams.Championships',
                'Dispositions' => [
                    'Members' => function (Query $q) use ($matchdayId) {
                        return $q->find(
                                    'list',
                                    [
                                        'keyField' => 'id',
                                        'valueField' => function ($obj) {
                                            return $obj;
                                        }
                                    ]
                                )
                                ->contain(
                                    ['Ratings' => function (Query $q) use ($matchdayId) {
                                            return $q->where(['Ratings.matchday_id' => $matchdayId]);
                                        }
                                    ]
                        );
                    }
        ]]);
    }
}
