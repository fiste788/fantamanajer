<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Rule\JollyAlreadyUsedRule;
use App\Model\Rule\LineupExpiredRule;
use App\Model\Rule\MissingPlayerInLineupRule;
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
 * Lineups Model
 *
 * @property \App\Model\Table\MembersTable|\App\Model\Table\BelongsTo $Members
 * @property \App\Model\Table\MembersTable|\App\Model\Table\BelongsTo $Members
 * @property \App\Model\Table\MembersTable|\App\Model\Table\BelongsTo $Members
 * @property \App\Model\Table\MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property \App\Model\Table\TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property \App\Model\Table\DispositionsTable|\Cake\ORM\Association\HasMany $Dispositions
 * @property \App\Model\Table\ScoresTable|\Cake\ORM\Association\HasOne $Scores
 * @property \App\Model\Table\View0LineupsDetailsTable|\Cake\ORM\Association\HasMany $View0LineupsDetails
 *
 * @method \App\Model\Entity\Lineup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Lineup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Lineup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Lineup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Lineup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Lineup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Lineup findOrCreate($search, callable $callback = null, $options = [])
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $Captain
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $VCaptain
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $VVCaptain
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Lineup|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class LineupsTable extends Table
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

        $this->setTable('lineups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior(
            'Timestamp',
            [
                'events' => [
                    'Model.beforeSave' => [
                        'created_at' => 'new',
                        'modified_at' => 'always',
                    ],
                ],
            ]
        );

        $this->belongsTo(
            'Captain',
            [
                'className' => 'Members',
                'foreignKey' => 'captain_id',
            ]
        );
        $this->belongsTo(
            'VCaptain',
            [
                'className' => 'Members',
                'foreignKey' => 'vcaptain_id',
            ]
        );
        $this->belongsTo(
            'VVCaptain',
            [
                'className' => 'Members',
                'foreignKey' => 'vvcaptain_id',
            ]
        );
        $this->belongsTo(
            'Matchdays',
            [
                'foreignKey' => 'matchday_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Teams',
            [
                'foreignKey' => 'team_id',
                'joinType' => 'INNER',
            ]
        );
        $this->hasMany(
            'Dispositions',
            [
                'foreignKey' => 'lineup_id',
                'sort' => ['Dispositions.position'],
                'saveStrategy' => 'replace',
            ]
        );
        $this->hasOne(
            'Scores',
            [
                'foreignKey' => 'lineup_id',
            ]
        );
        $this->hasMany(
            'View0LineupsDetails',
            [
                'foreignKey' => 'lineup_id',
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
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['captain_id'], 'Captain'));
        $rules->add($rules->existsIn(['vcaptain_id'], 'VCaptain'));
        $rules->add($rules->existsIn(['vvcaptain_id'], 'VVCaptain'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->isUnique(['team_id', 'matchday_id'], __('Lineup already exists for this matchday. Try to refresh')));
        $rules->addCreate(
            new LineupExpiredRule(),
            'Expired',
            ['errorField' => 'module', 'message' => __('Expired lineup')]
        );
        $rules->add(
            new MissingPlayerInLineupRule(),
            'MissingPlayer',
            ['errorField' => 'module', 'message' => __('Missing player/s')]
        );

        $rules->add(
            new JollyAlreadyUsedRule(),
            'JollyAlreadyUsed',
            ['errorField' => 'jolly', 'message' => __('Jolly already used')]
        );

        return $rules;
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew()) {
            $event = new Event('Fantamanajer.newLineup', $this, [
                'lineup' => $entity,
            ]);
            EventManager::instance()->dispatch($event);
        }
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options): void
    {
        if ($data->offsetExists('created_at')) {
            unset($data['created_at']);
        }
        if ($data->offsetExists('modified_at')) {
            unset($data['modified_at']);
        }
    }

    public function findDetails(Query $q, array $options): Query
    {
        return $q->contain([
            'Teams',
            'Dispositions' => [
                'Members' => [
                    'Roles', 'Players', 'Clubs', 'Ratings' => function ($q) use ($options) {
                        return $q->where(['matchday_id' => $options['matchday_id']]);
                    },
                ],
            ],
        ])->where([
            'team_id' => $options['team_id'],
            'matchday_id' => $options['matchday_id'],
        ]);
    }

    /**
     *
     * @param \Cake\ORM\Query $q
     * @param array $options
     * @return \Cake\ORM\Query
     */
    public function findLast(Query $q, array $options): Query
    {
        $q->innerJoinWith('Matchdays')
            ->contain(['Dispositions'])
            ->where([
                'Lineups.team_id' => $options['team_id'],
                'Lineups.matchday_id <=' => $options['matchday']->id,
                'Matchdays.season_id' => $options['matchday']->season_id,
            ])
            ->order(['Matchdays.number' => 'DESC']);
        if (array_key_exists('stats', $options) && $options['stats']) {
            $seasonId = $options['matchday']->season_id;
            $tableLocator = TableRegistry::getTableLocator();
            $q->contain([
                'Teams' => [
                    'Members' => function (Query $q) use ($seasonId, $tableLocator) {
                        return $q->find('withStats', ['season_id' => $seasonId])
                            ->select($tableLocator->get('Roles'))
                            ->select($tableLocator->get('Players'))
                            ->select($tableLocator->get('VwMembersStats'))
                            ->select(['id', 'role_id'])
                            ->contain(['Roles', 'Players']);
                    },
                ],
            ]);
        }

        return $q;
    }

    public function findByMatchdayIdAndTeamId(Query $q, array $options): Query
    {
        return $q->contain(['Dispositions'])
            ->where([
                'Lineups.team_id' => $options['team_id'],
                'Lineups.matchday_id =' => $options['matchday_id'],
            ]);
    }

    public function findWithRatings(Query $q, array $options): Query
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
                            },
                        ]
                    )
                        ->contain(
                            ['Ratings' => function (Query $q) use ($matchdayId) {
                                return $q->where(['Ratings.matchday_id' => $matchdayId]);
                            }]
                        );
                },
            ],
        ]);
    }
}
