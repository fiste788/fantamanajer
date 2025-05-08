<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Season;
use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Matchdays Model
 *
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\SeasonsTable> $Seasons
 * @property \Cake\ORM\Association\HasMany<\App\Model\Table\ArticlesTable> $Articles
 * @property \Cake\ORM\Association\HasMany<\App\Model\Table\LineupsTable> $Lineups
 * @property \Cake\ORM\Association\HasMany<\App\Model\Table\RatingsTable> $Ratings
 * @property \Cake\ORM\Association\HasMany<\App\Model\Table\ScoresTable> $Scores
 * @property \Cake\ORM\Association\HasMany<\App\Model\Table\SelectionsTable> $Selections
 * @property \Cake\ORM\Association\HasMany<\App\Model\Table\TransfertsTable> $Transferts
 * @method \App\Model\Entity\Matchday get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Matchday newEntity(array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\Matchday> newEntities(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Matchday|false save(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Matchday saveOrFail(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Matchday patchEntity(\Cake\Datasource\EntityInterface $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\Matchday> patchEntities(iterable<\Cake\Datasource\EntityInterface> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Matchday findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Matchday newEmptyEntity()
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Matchday>|false saveMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Matchday> saveManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Matchday>|false deleteMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Matchday> deleteManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 */
class MatchdaysTable extends Table
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

        $this->setTable('matchdays');
        $this->setDisplayField('number');
        $this->setPrimaryKey('id');

        $this->belongsTo('Seasons', [
            'foreignKey' => 'season_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Articles', [
            'foreignKey' => 'matchday_id',
        ]);
        $this->hasMany('Lineups', [
            'foreignKey' => 'matchday_id',
        ]);
        $this->hasMany('Ratings', [
            'foreignKey' => 'matchday_id',
        ]);
        $this->hasMany('Scores', [
            'foreignKey' => 'matchday_id',
        ]);
        $this->hasMany('Selections', [
            'foreignKey' => 'matchday_id',
        ]);
        $this->hasMany('Transferts', [
            'foreignKey' => 'matchday_id',
        ]);
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('number')
            ->requirePresence('number', 'create')
            ->notEmptyString('number');

        $validator
            ->dateTime('date')
            ->requirePresence('date', 'create')
            ->notEmptyDateTime('date');

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
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));

        return $rules;
    }

    /**
     * Find current query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findCurrent(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $interval = array_key_exists('interval', $args) ? (int)$args['interval'] : 0;
        $now = new DateTime();
        $now->addMinutes($interval);

        return $query->contain(['Seasons'])
            ->where(['date > ' => $now])
            ->orderByAsc('number')->limit(1);
    }

    /**
     * Find previuos query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed $args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findPrevious(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $interval = array_key_exists('interval', $args) ? (int)$args['interval'] : 0;
        $now = new DateTime();
        $now->addMinutes($interval);

        return $query->contain(['Seasons'])
            ->where(['date < ' => $now])
            ->orderByDesc('date')->limit(1);
    }

    /**
     * @param \App\Model\Entity\Season $season Season
     * @return array<\App\Model\Entity\Matchday>
     */
    public function findWithoutScores(Season $season): array
    {
        $query = $this->find();

        /** @var array<\App\Model\Entity\Matchday> $res */
        $res = $query->leftJoinWith('Scores')
            ->contain('Seasons')
            ->where(
                [
                    'team_id IS' => null,
                    'date <' => new DateTime(),
                    'season_id' => $season->id,
                ],
            )
            ->toArray();

        return $res;
    }

    /**
     * Find with scores
     *
     * @param \App\Model\Entity\Season $season Season
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findWithScores(Season $season): SelectQuery
    {
        return $this->find()
            ->innerJoinWith('Scores')
            ->where(['season_id' => $season->id])
            ->orderByDesc('Matchdays.id')
            ->limit(1);
    }

    /**
     * Find first without score query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findFirstWithoutScores(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->select('Matchdays.id')
            ->leftJoinWith('Scores')
            ->orderByAsc('Matchdays.number')
            ->whereNull('Scores.id')->andWhere([
                'Matchdays.number >' => 0,
                'season_id' => $args['season'],
            ])->limit(1);
    }

    /**
     * @param \App\Model\Entity\Season $season Season
     * @return array<\App\Model\Entity\Matchday>
     */
    public function findWithoutRatings(Season $season): array
    {
        $query = $this->find();

        /** @var array<\App\Model\Entity\Matchday> $res */
        $res = $query->leftJoinWith('Ratings')
            ->contain('Seasons')
            ->where(
                [
                    'number !=' => 0,
                    'member_id IS' => null,
                    'date <' => new DateTime(),
                    'season_id' => $season->id,
                ],
            )
            ->toArray();

        return $res;
    }

    /**
     * Find with rarings
     *
     * @param \App\Model\Entity\Season $season Season
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findWithRatings(Season $season): SelectQuery
    {
        return $this->find()
            ->innerJoinWith('Ratings')
            ->where(['season_id' => $season->id])
            ->orderByDesc('Matchdays.id')
            ->limit(1);
    }
}
