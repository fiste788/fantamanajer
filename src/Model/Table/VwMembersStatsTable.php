<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VwMembersStats Model
 *
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $Members
 * @method \App\Model\Entity\VwMembersStat get($primaryKey, $options = [])
 * @method \App\Model\Entity\VwMembersStat newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VwMembersStat[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VwMembersStat|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VwMembersStat patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VwMembersStat[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VwMembersStat findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\VwMembersStat|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class VwMembersStatsTable extends Table
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('vw_members_stats');

        $this->belongsTo(
            'Members',
            [
                'foreignKey' => 'member_id',
                'joinType' => 'INNER',
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('sum_present')
            ->allowEmpty('sum_present');

        $validator
            ->integer('sum_valued')
            ->allowEmpty('sum_valued');

        $validator
            ->numeric('avg_points')
            ->allowEmpty('avg_points');

        $validator
            ->numeric('avg_rating')
            ->allowEmpty('avg_rating');

        $validator
            ->integer('sum_goals')
            ->allowEmpty('sum_goals');

        $validator
            ->integer('sum_goals_against')
            ->allowEmpty('sum_goals_against');

        $validator
            ->integer('sum_assist')
            ->allowEmpty('sum_assist');

        $validator
            ->integer('sum_yellow_card')
            ->allowEmpty('sum_yellow_card');

        $validator
            ->integer('sum_red_card')
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
     * @param  \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['member_id'], 'Members'));

        return $rules;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Database\Schema\TableSchema $schema Schema
     * @return \Cake\Database\Schema\TableSchema
     */
    protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface
    {
        $schema->setColumnType('sum_present', 'integer');
        $schema->setColumnType('sum_assist', 'integer');
        $schema->setColumnType('sum_goals', 'integer');
        $schema->setColumnType('sum_goals_against', 'integer');
        $schema->setColumnType('sum_red_card', 'integer');
        $schema->setColumnType('sum_yellow_card', 'integer');
        $schema->setColumnType('sum_valued', 'integer');

        return $schema;
    }
}
