<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Championships Model
 *
 * @property \App\Model\Table\LeaguesTable&\Cake\ORM\Association\BelongsTo $Leagues
 * @property \App\Model\Table\SeasonsTable&\Cake\ORM\Association\BelongsTo $Seasons
 * @property \App\Model\Table\RollOfHonorsTable&\Cake\ORM\Association\BelongsTo $RollOfHonors
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\HasMany $Teams
 * @method \App\Model\Entity\Championship get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Championship newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Championship[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Championship|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Championship saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Championship patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Championship[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Championship findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Championship newEmptyEntity()
 * @method \App\Model\Entity\Championship[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Championship[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Championship[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Championship[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 */
class ChampionshipsTable extends Table
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

        $this->setTable('championships');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Leagues', [
            'foreignKey' => 'league_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Seasons', [
            'foreignKey' => 'season_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Teams', [
            'foreignKey' => 'championship_id',
        ]);
        $this->hasMany('RollOfHonors', [
            'foreignKey' => 'championship_id',
            'propertyName' => 'roll_of_honor',
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
            ->boolean('started')
            ->notEmptyString('started');

        $validator
            ->boolean('captain')
            ->notEmptyString('captain');

        $validator
            ->boolean('jolly')
            ->notEmptyString('jolly');

        $validator
            ->boolean('captain_missed_lineup')
            ->notEmptyString('captain_missed_lineup');

        $validator
            ->boolean('bonus_points_goals')
            ->notEmptyString('bonus_points_goals');

        $validator
            ->boolean('bonus_points_clean_sheet')
            ->notEmptyString('bonus_points_clean_sheet');

        $validator
            ->notEmptyString('minute_lineup');

        $validator
            ->notEmptyString('points_missed_lineup');

        $validator
            ->notEmptyString('number_selections');

        $validator
            ->notEmptyString('number_transferts');

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
        $rules->add($rules->existsIn(['league_id'], 'Leagues'));
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));

        return $rules;
    }

    /**
     * Find by team id query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByLeagueId(SelectQuery $query, mixed ...$args): SelectQuery
    {
        $teams = $this->RollOfHonors->Teams;

        return $query->select(['id'])
            ->select(['Seasons.id', 'Seasons.name'])
            ->contain(['Seasons', 'RollOfHonors' => function (SelectQuery $q) use ($teams): SelectQuery {
                return $q->select(['championship_id', 'rank', 'points'])
                    ->contain(['Teams' => function (SelectQuery $q) use ($teams): SelectQuery {
                        return $q->select($teams)
                            ->select(['Users.id', 'Users.name', 'Users.surname'])
                            ->contain(['Users']);
                    }])->orderBy(['rank' => 'ASC']);
            }])
            ->where([
                'Championships.league_id' => $args['league_id'],
            ])->orderBy(['Seasons.year' => 'DESC']);
    }
}
