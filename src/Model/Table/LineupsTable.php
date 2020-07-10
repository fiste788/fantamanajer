<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Member;
use App\Model\Rule\JollyAlreadyUsedRule;
use App\Model\Rule\LineupExpiredRule;
use App\Model\Rule\MissingPlayerInLineupRule;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Lineups Model
 *
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $Captain
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $VCaptain
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $VVCaptain
 * @property \App\Model\Table\MatchdaysTable&\Cake\ORM\Association\BelongsTo $Matchdays
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\BelongsTo $Teams
 * @property \App\Model\Table\DispositionsTable&\Cake\ORM\Association\HasMany $Dispositions
 * @property \App\Model\Table\ScoresTable&\Cake\ORM\Association\HasOne $Scores
 * @method \App\Model\Entity\Lineup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Lineup newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Lineup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Lineup|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Lineup saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Lineup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Lineup[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Lineup findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Lineup newEmptyEntity()
 * @method \App\Model\Entity\Lineup[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Lineup[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Lineup[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Lineup[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LineupsTable extends Table
{
    use LocatorAwareTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     * @throws \RuntimeException
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('lineups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Members', [
            'foreignKey' => 'captain_id',
        ]);
        $this->belongsTo('Members', [
            'foreignKey' => 'vcaptain_id',
        ]);
        $this->belongsTo('Members', [
            'foreignKey' => 'vvcaptain_id',
        ]);
        $this->belongsTo('Matchdays', [
            'foreignKey' => 'matchday_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Dispositions', [
            'foreignKey' => 'lineup_id',
            'sort' => ['Dispositions.position'],
            'saveStrategy' => 'replace',
        ]);
        $this->hasOne('Scores', [
            'foreignKey' => 'lineup_id',
        ]);
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'modified_at' => 'always',
                ],
            ],
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
            ->scalar('module')
            ->maxLength('module', 7)
            ->requirePresence('module', 'create')
            ->notEmptyString('module');

        $validator
            ->boolean('jolly')
            ->allowEmptyString('jolly');

        $validator
            ->boolean('cloned')
            ->allowEmptyString('cloned');

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
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['captain_id'], 'Members'));
        $rules->add($rules->existsIn(['vcaptain_id'], 'Members'));
        $rules->add($rules->existsIn(['vvcaptain_id'], 'Members'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add(
            $rules->isUnique(['team_id', 'matchday_id'], __('Lineup already exists for this matchday. Try to refresh'))
        );
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

    /**
     * @inheritDoc
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew()) {
            $event = new Event('Fantamanajer.newLineup', $this, [
                'lineup' => $entity,
            ]);
            EventManager::instance()->dispatch($event);
        }
    }

    /**
     * @inheritDoc
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options): void
    {
        if ($data->offsetExists('created_at')) {
            $data->offsetUnset('created_at');
        }
        if ($data->offsetExists('modified_at')) {
            $data->offsetUnset('modified_at');
        }
    }

    /**
     * Find details query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findDetails(Query $q, array $options): Query
    {
        return $q->contain([
            'Teams',
            'Dispositions' => [
                'Members' => [
                    'Roles', 'Players', 'Clubs', 'Ratings' => function (Query $q): Query {
                        return $q->where([
                            'matchday_id' => new \Cake\Database\Expression\IdentifierExpression('Lineups.matchday_id'),
                        ]);
                    },
                ],
            ],
        ])->where([
            'team_id' => $options['team_id'],
            'matchday_id' => $options['matchday_id'],
        ]);
    }

    /**
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findLast(Query $q, array $options): Query
    {
        /** @var \App\Model\Entity\Matchday $matchday */
        $matchday = $options['matchday'];
        $q = $q->innerJoinWith('Matchdays')
            ->contain(['Dispositions'])
            ->where([
                'Lineups.team_id' => $options['team_id'],
                'Lineups.matchday_id <=' => $matchday->id,
                'Matchdays.season_id' => $matchday->season_id,
            ])
            ->order(['Matchdays.number' => 'DESC']);
        if (array_key_exists('stats', $options) && $options['stats']) {
            $seasonId = $matchday->season_id;
            $q = $q->contain([
                'Teams' => [
                    'Members' => function (Query $q) use ($seasonId): Query {
                        return $q->find('withStats', ['season_id' => $seasonId])
                            ->select($this->getTableLocator()->get('Roles'))
                            ->select($this->getTableLocator()->get('Players'))
                            ->select($this->getTableLocator()->get('MembersStats'))
                            ->select(['id', 'role_id', 'club_id'])
                            ->contain(['Roles', 'Players']);
                    },
                ],
            ]);
        }

        return $q;
    }

    /**
     * Find by matchday and team query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findByMatchdayIdAndTeamId(Query $q, array $options): Query
    {
        return $q->contain(['Dispositions'])
            ->where([
                'Lineups.team_id' => $options['team_id'],
                'Lineups.matchday_id =' => $options['matchday_id'],
            ]);
    }

    /**
     * Find with ratings query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findWithRatings(Query $q, array $options): Query
    {
        $matchdayId = (int)$options['matchday_id'];

        return $q->contain([
            'Teams.Championships',
            'Dispositions' => [
                'Members' => function (Query $q) use ($matchdayId): Query {
                    return $q->find(
                        'list',
                        [
                            'keyField' => 'id',
                            'valueField' => function (Member $obj): Member {
                                return $obj;
                            },
                        ]
                    )
                        ->contain(
                            ['Roles', 'Ratings' => function (Query $q) use ($matchdayId): Query {
                                return $q->where(['Ratings.matchday_id' => $matchdayId]);
                            }]
                        );
                },
            ],
        ]);
    }
}
