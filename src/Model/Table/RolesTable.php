<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Roles Model
 *
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\HasMany $Members
 * @property \Cake\ORM\Association\HasMany $View0LineupsDetails
 * @property \Cake\ORM\Association\HasMany $View0Members
 * @property \Cake\ORM\Association\HasMany $View1MembersStats
 * @method \App\Model\Entity\Role get($primaryKey, $options = [])
 * @method \App\Model\Entity\Role newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Role[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Role|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Role patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Role[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Role findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Role|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class RolesTable extends Table
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

        $this->setTable('roles');
        $this->setDisplayField('singolar');
        $this->setPrimaryKey('id');

        $this->hasMany(
            'Members',
            [
                'foreignKey' => 'role_id',
            ]
        );
        $this->hasMany(
            'View0LineupsDetails',
            [
                'foreignKey' => 'role_id',
            ]
        );
        $this->hasMany(
            'View0Members',
            [
                'foreignKey' => 'role_id',
            ]
        );
        $this->hasMany(
            'View1MembersStats',
            [
                'foreignKey' => 'role_id',
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('singolar', 'create')
            ->notEmpty('singolar');

        $validator
            ->requirePresence('plural', 'create')
            ->notEmpty('plural');

        $validator
            ->requirePresence('abbreviation', 'create')
            ->notEmpty('abbreviation');

        $validator
            ->requirePresence('determinant', 'create')
            ->notEmpty('determinant');

        return $validator;
    }
}
