<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Roles Model
 *
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\HasMany $Members
 * @method \App\Model\Entity\Role get($primaryKey, $options = [])
 * @method \App\Model\Entity\Role newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Role[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Role|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Role saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Role patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Role[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Role findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Role newEmptyEntity()
 * @method \App\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class RolesTable extends Table
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

        $this->setTable('roles');
        $this->setDisplayField('singolar');
        $this->setPrimaryKey('id');

        $this->hasMany('Members', [
            'foreignKey' => 'role_id',
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
            ->scalar('singolar')
            ->maxLength('singolar', 32)
            ->requirePresence('singolar', 'create')
            ->notEmptyString('singolar');

        $validator
            ->scalar('plural')
            ->maxLength('plural', 32)
            ->requirePresence('plural', 'create')
            ->notEmptyString('plural');

        $validator
            ->scalar('abbreviation')
            ->maxLength('abbreviation', 32)
            ->requirePresence('abbreviation', 'create')
            ->notEmptyString('abbreviation');

        $validator
            ->scalar('determinant')
            ->maxLength('determinant', 5)
            ->notEmptyString('determinant');

        return $validator;
    }
}
