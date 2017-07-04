<?php
namespace App\Model\Table;

use App\Model\Entity\VwMembersStat;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VwMembersStats Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Members
 */
class VwMembersStatsTable extends Table
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

        $this->table('vw_members_stats');

        $this->belongsTo('Members', [
            'foreignKey' => 'member_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->decimal('sum_present')
            ->allowEmpty('sum_present');

        $validator
            ->decimal('sum_valued')
            ->allowEmpty('sum_valued');

        $validator
            ->numeric('avg_points')
            ->allowEmpty('avg_points');

        $validator
            ->numeric('avg_rating')
            ->allowEmpty('avg_rating');

        $validator
            ->decimal('sum_goals')
            ->allowEmpty('sum_goals');

        $validator
            ->decimal('sum_goals_against')
            ->allowEmpty('sum_goals_against');

        $validator
            ->decimal('sum_assist')
            ->allowEmpty('sum_assist');

        $validator
            ->decimal('sum_yellow_card')
            ->allowEmpty('sum_yellow_card');

        $validator
            ->decimal('sum_red_card')
            ->allowEmpty('sum_red_card');

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
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['member_id'], 'Members'));
        return $rules;
    }
}
