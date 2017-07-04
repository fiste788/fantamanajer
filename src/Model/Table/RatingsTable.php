<?php
namespace App\Model\Table;

use Cake\ORM\Association\BelongsTo;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ratings Model
 *
 * @property BelongsTo $Members
 * @property BelongsTo $Matchdays
 */
class RatingsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('ratings');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Members', [
            'foreignKey' => 'member_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Matchdays', [
            'foreignKey' => 'matchday_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
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
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['member_id'], 'Members'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        return $rules;
    }
}
