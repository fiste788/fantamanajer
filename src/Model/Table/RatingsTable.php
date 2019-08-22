<?php
namespace App\Model\Table;

use App\Model\Entity\Season;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ratings Model
 *
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $Members
 * @property \App\Model\Table\MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 *
 * @method \App\Model\Entity\Rating get($primaryKey, $options = [])
 * @method \App\Model\Entity\Rating newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Rating[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Rating|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Rating patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Rating[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Rating findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Rating|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class RatingsTable extends Table
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

        $this->setTable('ratings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Members',
            [
            'foreignKey' => 'member_id',
            'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Matchdays',
            [
            'foreignKey' => 'matchday_id',
            'joinType' => 'INNER'
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
            ->boolean('valued')
            ->requirePresence('valued', 'create')
            ->notEmpty('valued');

        $validator
            ->numeric('points')
            ->requirePresence('points', 'create')
            ->notEmpty('points');

        $validator
            ->numeric('rating')
            ->requirePresence('rating', 'create')
            ->notEmpty('rating');

        $validator
            ->integer('goals')
            ->requirePresence('goals', 'create')
            ->notEmpty('goals');

        $validator
            ->integer('goals_against')
            ->requirePresence('goals_against', 'create')
            ->notEmpty('goals_against');

        $validator
            ->integer('goals_victory')
            ->requirePresence('goals_victory', 'create')
            ->notEmpty('goals_victory');

        $validator
            ->integer('goals_tie')
            ->requirePresence('goals_tie', 'create')
            ->notEmpty('goals_tie');

        $validator
            ->integer('assist')
            ->requirePresence('assist', 'create')
            ->notEmpty('assist');

        $validator
            ->boolean('yellow_card')
            ->requirePresence('yellow_card', 'create')
            ->notEmpty('yellow_card');

        $validator
            ->boolean('red_card')
            ->requirePresence('red_card', 'create')
            ->notEmpty('red_card');

        $validator
            ->integer('penalities_scored')
            ->requirePresence('penalities_scored', 'create')
            ->notEmpty('penalities_scored');

        $validator
            ->integer('penalities_taken')
            ->requirePresence('penalities_taken', 'create')
            ->notEmpty('penalities_taken');

        $validator
            ->boolean('present')
            ->requirePresence('present', 'create')
            ->notEmpty('present');

        $validator
            ->boolean('regular')
            ->requirePresence('regular', 'create')
            ->notEmpty('regular');

        $validator
            ->integer('quotation')
            ->requirePresence('quotation', 'create')
            ->notEmpty('quotation');

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
        $rules->add($rules->existsIn(['member_id'], 'Members'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));

        return $rules;
    }

    public function existMatchday($matchday)
    {
        return $this->exists(['matchday_id' => $matchday->id]);
    }

    /**
     *
     * @param Season $season
     * @return int
     */
    public function findMaxMatchday(Season $season)
    {
        $query = $this->find();
        $res = $query->disableHydration()
            ->leftJoinWith('Matchdays')
            ->select(['matchday_id' => $query->func()->max('Scores.matchday_id')])
            ->where(['m.season_id' => $season->id])
            ->first();

        return $res['matchday_id'];
    }
}
