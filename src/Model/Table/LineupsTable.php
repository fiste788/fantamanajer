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
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query\SelectQuery;
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
 * @method \App\Model\Entity\Lineup get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Lineup newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Lineup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Lineup|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Lineup saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Lineup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Lineup[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Lineup findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Lineup newEmptyEntity()
 * @method \App\Model\Entity\Lineup[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Lineup[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Lineup[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Lineup[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $Members
 */
class LineupsTable extends Table
{
    use LocatorAwareTrait;

    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     * @throws \RuntimeException
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('lineups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Captain', [
            'className' => 'Members',
            'foreignKey' => 'captain_id',
        ]);
        $this->belongsTo('Vcaptain', [
            'className' => 'Members',
            'foreignKey' => 'vcaptain_id',
        ]);
        $this->belongsTo('VVcaptain', [
            'className' => 'Members',
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
     * @throws \Cake\Core\Exception\CakeException
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
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findDetails(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->contain([
            'Teams',
            'Dispositions' => [
                'Members' => [
                    'Roles',
                    'Players',
                    'Clubs',
                    'Ratings' => function (SelectQuery $q) use ($args): SelectQuery {
                        return $q->where(['matchday_id' => $args['matchday_id']]);
                    },
                ],
            ],
        ])->where([
            'team_id' => $args['team_id'],
            'matchday_id' => $args['matchday_id'],
        ]);
    }

    /**
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findLast(SelectQuery $query, mixed ...$args): SelectQuery
    {
        /** @var \App\Model\Entity\Matchday $matchday */
        $matchday = $args['matchday'];
        $query = $query->innerJoinWith('Matchdays')
            ->contain(['Dispositions', 'Matchdays'])
            ->where([
                'Lineups.team_id' => $args['team_id'],
                'Lineups.matchday_id <=' => $matchday->id,
                'Matchdays.season_id' => $matchday->season_id,
            ])
            ->orderBy(['Matchdays.number' => 'DESC']);
        if (array_key_exists('stats', $args) && $args['stats']) {
            $seasonId = $matchday->season_id;
            $query = $query->contain([
                'Teams' => [
                    'Members' => function (SelectQuery $q) use ($seasonId): SelectQuery {
                        return $q->find('withStats', season_id: $seasonId)
                            ->select($this->getTableLocator()->get('Roles'))
                            ->select($this->getTableLocator()->get('Players'))
                            ->select($this->getTableLocator()->get('MembersStats'))
                            ->select(['id', 'role_id', 'club_id'])
                            ->contain(['Roles', 'Players']);
                    },
                ],
            ]);
        }

        return $query;
    }

    /**
     * Find by matchday and team query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByMatchdayIdAndTeamId(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->contain(['Dispositions'])
            ->where([
                'Lineups.team_id' => $args['team_id'],
                'Lineups.matchday_id =' => $args['matchday_id'],
            ]);
    }

    /**
     * Find with ratings query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findWithRatings(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $matchdayId = (int)$args['matchday_id'];

        return $query->contain([
            'Teams.Championships',
            'Dispositions' => [
                'Members' => function (SelectQuery $q) use ($matchdayId): SelectQuery {
                    return $q->find('listWithRating', matchday_id: $matchdayId);
                },
            ],
        ]);
    }
}
