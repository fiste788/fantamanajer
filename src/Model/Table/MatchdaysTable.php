<?php

namespace App\Model\Table;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use App\Model\Table\ArticlesTable;
use App\Model\Table\LineupsTable;
use App\Model\Table\RatingsTable;
use App\Model\Table\ScoresTable;
use App\Model\Table\SeasonsTable;
use App\Model\Table\TransfertsTable;
use Cake\Datasource\EntityInterface;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Matchdays Model
 *
 * @property SeasonsTable|\Cake\ORM\Association\BelongsTo $Seasons
 * @property ArticlesTable|\Cake\ORM\Association\HasMany $Articles
 * @property LineupsTable|\Cake\ORM\Association\HasMany $Lineups
 * @property RatingsTable|\Cake\ORM\Association\HasMany $Ratings
 * @property ScoresTable|\Cake\ORM\Association\HasMany $Scores
 * @property TransfertsTable|\Cake\ORM\Association\HasMany $Transferts
 *
 * @method Matchday get($primaryKey, $options = [])
 * @method Matchday newEntity($data = null, array $options = [])
 * @method Matchday[] newEntities(array $data, array $options = [])
 * @method Matchday|bool save(EntityInterface $entity, $options = [])
 * @method Matchday patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Matchday[] patchEntities($entities, array $data, array $options = [])
 * @method Matchday findOrCreate($search, callable $callback = null, $options = [])
 * @method Matchday|bool saveOrFail(EntityInterface $entity, $options = [])
 */
class MatchdaysTable extends Table
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

        $this->setTable('matchdays');
        $this->setDisplayField('number');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Seasons',
            [
                'foreignKey' => 'season_id',
                'joinType' => 'INNER'
            ]
        );
        $this->hasMany(
            'Articles',
            [
                'foreignKey' => 'matchday_id'
            ]
        );
        $this->hasMany(
            'Lineups',
            [
                'foreignKey' => 'matchday_id'
            ]
        );
        $this->hasMany(
            'Ratings',
            [
                'foreignKey' => 'matchday_id'
            ]
        );
        $this->hasMany(
            'Scores',
            [
                'foreignKey' => 'matchday_id'
            ]
        );
        $this->hasMany(
            'Transferts',
            [
                'foreignKey' => 'matchday_id'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('number')
            ->requirePresence('number', 'create')
            ->notEmpty('number');

        $validator
            ->dateTime('date')
            ->requirePresence('date', 'create')
            ->notEmpty('date');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));

        return $rules;
    }

    public function findCurrent(Query $q, array $options)
    {
        $interval = array_key_exists('interval', $options) ? $options['interval'] : 0;
        $now = new Time();
        $now->addMinute($interval);

        return $q->contain(['Seasons'])
            ->where(['date > ' => $now])
            ->orderAsc('number');
    }

    /**
     *
     * @param Season $season
     * @return Matchday[]
     */
    public function findWithoutScores(Season $season)
    {
        $query = $this->find();
        $res = $query->leftJoinWith("Scores")
            ->contain('Seasons')
            ->where(
                [
                    'team_id IS' => null,
                    'date <' => new DateTime(),
                    'season_id' => $season->id
                ]
            )
            ->toArray();

        return $res;
    }

    public function findWithScores(Season $season)
    {
        return $this->find()
            ->innerJoinWith('Scores')
            ->where(['season_id' => $season->id])
            ->orderDesc('Matchdays.id')
            ->limit(1);
    }

    public function findFirstWithoutScores(Query $q, array $options)
    {
        return $q->select('Matchdays.id')
            ->leftJoinWith('Scores')
            ->orderAsc('Matchdays.number')
            ->whereNull('Scores.id')->andWhere([
                'Matchdays.number >' => 0,
                'season_id' => $options['season']
            ])->limit(1);
    }

    /**
     *
     * @param Season $season
     * @return Matchday[]
     */
    public function findWithoutRatings(Season $season)
    {
        $query = $this->find();
        $res = $query->leftJoinWith("Ratings")
            ->contain('Seasons')
            ->where(
                [
                    'number !=' => 0,
                    'member_id IS' => null,
                    'date <' => new DateTime(),
                    'season_id' => $season->id
                ]
            )
            ->toArray();

        return $res;
    }

    /**
     *
     * @param Season $season
     * @return int
     */
    public function findWithRatings(Season $season)
    {
        return $this->find()
            ->innerJoinWith('Ratings')
            ->where(['season_id' => $season->id])
            ->orderDesc('Matchdays.id')
            ->limit(1);
    }
}
