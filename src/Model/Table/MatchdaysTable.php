<?php
namespace App\Model\Table;

use App\Model\Entity\Matchday;
use App\Model\Entity\Season;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use DateTime;

/**
 * Matchdays Model
 *
 * @property \App\Model\Table\SeasonsTable|\Cake\ORM\Association\BelongsTo $Seasons
 * @property \App\Model\Table\ArticlesTable|\Cake\ORM\Association\HasMany $Articles
 * @property \App\Model\Table\LineupsTable|\Cake\ORM\Association\HasMany $Lineups
 * @property \App\Model\Table\RatingsTable|\Cake\ORM\Association\HasMany $Ratings
 * @property \App\Model\Table\ScoresTable|\Cake\ORM\Association\HasMany $Scores
 * @property \App\Model\Table\TransfertsTable|\Cake\ORM\Association\HasMany $Transferts
 *
 * @method \App\Model\Entity\Matchday get($primaryKey, $options = [])
 * @method \App\Model\Entity\Matchday newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Matchday[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Matchday|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Matchday patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Matchday[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Matchday findOrCreate($search, callable $callback = null, $options = [])
 */
class MatchdaysTable extends Table
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
    public function validationDefault(Validator $validator)
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
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));

        return $rules;
    }

    public function findCurrent()
    {
        return $this
            ->find('all')
            ->contain(['Seasons'])
            ->where(
                [
                'date > ' => new DateTime('now')
                ]
            )
            ->first();
    }

    public function getTargetCountdown($minutes = 0)
    {
        $query = $this->find();
        $expr = $query->newExpr()->add('MIN(date) - INTERVAL ' . $minutes . ' MINUTE');
        $query->select(['date' => $expr])->where(['NOW() < date']);

        return $query->first()->date;
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
            ->where(['Seasons.id' => $season->id])
            ->orderDesc('Matchdays.id')
            ->limit(1);
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
            ->innerJoinWith('Seasons')
            ->where(['Seasons.id' => $season->id])
            ->orderDesc('Matchdays.id')
            ->limit(1);
    }
}
