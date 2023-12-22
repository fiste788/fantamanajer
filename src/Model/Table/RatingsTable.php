<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ratings Model
 *
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $Members
 * @property \App\Model\Table\MatchdaysTable&\Cake\ORM\Association\BelongsTo $Matchdays
 * @method \App\Model\Entity\Rating get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Rating newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Rating[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Rating|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Rating saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Rating patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Rating[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Rating findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Rating newEmptyEntity()
 * @method \App\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Rating[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 */
class RatingsTable extends Table
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

        $this->setTable('ratings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Members', [
            'foreignKey' => 'member_id',
            'joinType' => 'INNER',
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
            ->boolean('valued')
            ->requirePresence('valued', 'create')
            ->notEmptyString('valued');

        $validator
            ->numeric('points')
            ->requirePresence('points', 'create')
            ->notEmptyString('points');

        $validator
            ->numeric('rating')
            ->requirePresence('rating', 'create')
            ->notEmptyString('rating');

        $validator
            ->integer('goals')
            ->requirePresence('goals', 'create')
            ->notEmptyString('goals');

        $validator
            ->integer('goals_against')
            ->requirePresence('goals_against', 'create')
            ->notEmptyString('goals_against');

        $validator
            ->integer('goals_victory')
            ->requirePresence('goals_victory', 'create')
            ->notEmptyString('goals_victory');

        $validator
            ->integer('goals_tie')
            ->requirePresence('goals_tie', 'create')
            ->notEmptyString('goals_tie');

        $validator
            ->integer('assist')
            ->requirePresence('assist', 'create')
            ->notEmptyString('assist');

        $validator
            ->boolean('yellow_card')
            ->requirePresence('yellow_card', 'create')
            ->notEmptyString('yellow_card');

        $validator
            ->boolean('red_card')
            ->requirePresence('red_card', 'create')
            ->notEmptyString('red_card');

        $validator
            ->integer('penalities_scored')
            ->requirePresence('penalities_scored', 'create')
            ->notEmptyString('penalities_scored');

        $validator
            ->integer('penalities_taken')
            ->requirePresence('penalities_taken', 'create')
            ->notEmptyString('penalities_taken');

        $validator
            ->boolean('present')
            ->requirePresence('present', 'create')
            ->notEmptyString('present');

        $validator
            ->boolean('regular')
            ->requirePresence('regular', 'create')
            ->notEmptyString('regular');

        $validator
            ->integer('quotation')
            ->requirePresence('quotation', 'create')
            ->notEmptyString('quotation');

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
        $rules->add($rules->existsIn(['member_id'], 'Members'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));

        return $rules;
    }

    /**
     * Exist matchday
     *
     * @param \App\Model\Entity\Matchday $matchday Matchday
     * @return bool
     */
    public function existMatchday(Matchday $matchday): bool
    {
        return $this->exists(['matchday_id' => $matchday->id]);
    }

    /**
     * Return max matchday
     *
     * @param \App\Model\Entity\Season $season Season
     * @return int|null
     */
    public function findMaxMatchday(Season $season): ?int
    {
        $query = $this->find();
        /** @var array<string, mixed> $res */
        $res = $query->disableHydration()
            ->leftJoinWith('Matchdays')
            ->select(['matchday_id' => $query->func()->max('Scores.matchday_id')])
            ->where(['m.season_id' => $season->id])
            ->first();

        return $res && $res['matchday_id'] ? (int)$res['matchday_id'] : null;
    }

    /**
     * Find by member id  query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByMemberId(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->select([
            'rating',
            'points',
            'goals',
            'goals_against',
            'assist',
            'penalities_scored',
            'penalities_taken',
            'regular',
            'yellow_card',
            'red_card',
            'quotation',
        ])->contain([
                    'Matchdays' => function (SelectQuery $q): SelectQuery {
                        return $q->select(['number'], true);
                    },
                ])->orderBy(['Matchdays.number' => 'ASC'])
            ->where(['member_id' => $args['member_id']]);
    }
}
