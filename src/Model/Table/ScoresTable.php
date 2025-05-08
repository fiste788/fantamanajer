<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Score;
use App\Model\Entity\Season;
use Cake\Collection\CollectionInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Scores Model
 *
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\LineupsTable> $Lineups
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\TeamsTable> $Teams
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\MatchdaysTable> $Matchdays
 * @method \App\Model\Entity\Score get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Score newEntity(array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\Score> newEntities(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Score|false save(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Score saveOrFail(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Score patchEntity(\Cake\Datasource\EntityInterface $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\Score> patchEntities(iterable<\Cake\Datasource\EntityInterface> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Score findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Score newEmptyEntity()
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Score>|false saveMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Score> saveManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Score>|false deleteMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Score> deleteManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 */
class ScoresTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('scores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Lineups', [
            'foreignKey' => 'lineup_id',
        ]);
        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
            'joinType' => 'RIGHT',
        ]);
        $this->belongsTo('Matchdays', [
            'foreignKey' => 'matchday_id',
            'joinType' => 'INNER',
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
            ->numeric('points')
            ->notEmptyString('points');

        $validator
            ->numeric('real_points')
            ->notEmptyString('real_points');

        $validator
            ->numeric('penality_points')
            ->notEmptyString('penality_points');

        $validator
            ->scalar('penality')
            ->maxLength('penality', 255)
            ->allowEmptyString('penality');

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
        $rules->add($rules->existsIn(['lineup_id'], 'Lineups'));
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));

        return $rules;
    }

    /**
     * @param \App\Model\Entity\Season $season Season
     * @return int|null
     */
    public function findMaxMatchday(Season $season): ?int
    {
        $query = $this->find();
        /** @var array<string, mixed> $res */
        $res = $query->disableHydration()
            ->join(
                [
                    'table' => 'matchdays',
                    'alias' => 'm',
                    'type' => 'LEFT',
                    'conditions' => 'm.id = Scores.matchday_id',
                ],
            )
            ->select(['matchday_id' => $query->func()->max('Scores.matchday_id'),])
            ->where(['m.season_id' => $season->id])
            ->first();

        return $res && $res['matchday_id'] ? (int)$res['matchday_id'] : null;
    }

    /**
     * Find scores query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     * @throws \InvalidArgumentException
     */
    public function findScores(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $championshipId = (int)$args['championship_id'];

        return $query->select(['id', 'points', 'team_id'])
            ->contain([
                'Matchdays' => ['fields' => ['number']],
            ])->innerJoinWith('Teams', function (SelectQuery $q) use ($championshipId): SelectQuery {
                return $q->where(['Teams.championship_id' => $championshipId]);
            })->formatResults(function (CollectionInterface $results): CollectionInterface {
                return $results->combine('matchday.number', function (Score $entity): Score {
                    unset($entity->matchday);

                    return $entity;
                }, 'team_id');
            }, true);
    }

    /**
     * Find by team query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByTeam(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->contain([
            'Teams',
            'Matchdays' => ['fields' => ['number']],
        ])->where([
            'team_id' => $args['team_id'],
        ]);
    }

    /**
     * Find ranking query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     * @throws \InvalidArgumentException
     */
    public function findRanking(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $championshipId = (int)$args['championship_id'];
        $sum = $query->func()->sum('points');
        $coalesce = $query->func()->coalesce([$sum, 0], ['float', 'float']);
        $query->select([
            'team_id',
            'sum_points' => $coalesce,
        ])->contain(['Teams' => ['fields' => ['id', 'name', 'championship_id']]])
            ->where(['Teams.championship_id' => $championshipId])
            ->groupBy('Teams.id')
            ->orderByDesc($sum);

        if (array_key_exists('scores', $args) && $args['scores']) {
            $query->formatResults(function (CollectionInterface $results) use ($championshipId): CollectionInterface {
                $scores = $this->find('scores', championship_id: $championshipId)->all()->toArray();

                if (!empty($scores)) {
                    return $results->map(function (Score $entity) use ($scores): Score {
                        $entity->set('scores', $scores[$entity->team_id]);

                        return $entity;
                    });
                } else {
                    return $results;
                }
            }, true);
        }

        return $query;
    }

    /**
     * Load score details
     *
     * @param \App\Model\Entity\Score $score Score
     * @param bool $members Members
     * @return \App\Model\Entity\Score
     */
    public function loadDetails(Score $score, bool $members = false): Score
    {
        if ($members) {
            $contain = [
                'Lineups' => [
                    'Dispositions',
                    'Teams.Members' => [
                        'Roles',
                        'Players',
                    ],
                ],
            ];
        } else {
            $contain = [
                'Lineups.Dispositions.Members' => [
                    'Roles',
                    'Players',
                    'Clubs',
                    'Ratings' => function (SelectQuery $q) use ($score): SelectQuery {
                        return $q->where(['Ratings.matchday_id' => $score->matchday_id]);
                    },
                ],
            ];
        }

        /** @var \App\Model\Entity\Score $details */
        $details = $this->loadInto($score, $contain);

        return $details;
    }
}
