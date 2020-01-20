<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MembersStats Model
 *
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $Members
 * @method \App\Model\Entity\MembersStat get($primaryKey, $options = [])
 * @method \App\Model\Entity\MembersStat newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MembersStat[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MembersStat|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MembersStat patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MembersStat[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MembersStat findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MembersStat saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MembersStat newEmptyEntity()
 * @method \App\Model\Entity\MembersStat[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MembersStat[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MembersStat[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MembersStat[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class MembersStatsTable extends Table
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('members_stats');

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
            ->allowEmptyString('sum_present');

        $validator
            ->integer('sum_valued')
            ->allowEmptyString('sum_valued');

        $validator
            ->numeric('avg_points')
            ->allowEmptyString('avg_points');

        $validator
            ->numeric('avg_rating')
            ->allowEmptyString('avg_rating');

        $validator
            ->integer('sum_goals')
            ->allowEmptyString('sum_goals');

        $validator
            ->integer('sum_goals_against')
            ->allowEmptyString('sum_goals_against');

        $validator
            ->integer('sum_assist')
            ->allowEmptyString('sum_assist');

        $validator
            ->integer('sum_yellow_card')
            ->allowEmptyString('sum_yellow_card');

        $validator
            ->integer('sum_red_card')
            ->allowEmptyString('sum_red_card');

        $validator
            ->integer('quotation')
            ->requirePresence('quotation', 'create')
            ->allowEmptyString('quotation');

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
     * @param \Cake\Database\Schema\TableSchemaInterface $schema Schema
     * @return \Cake\Database\Schema\TableSchemaInterface
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
