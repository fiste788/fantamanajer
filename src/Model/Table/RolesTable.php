<?php
namespace App\Model\Table;

use App\Model\Entity\Role;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Roles Model
 *
 * @property \Cake\ORM\Association\HasMany $Members
 * @property \Cake\ORM\Association\HasMany $View0LineupsDetails
 * @property \Cake\ORM\Association\HasMany $View0Members
 * @property \Cake\ORM\Association\HasMany $View1MembersStats
 */
class RolesTable extends Table
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

        $this->table('roles');
        $this->displayField('singolar');
        $this->primaryKey('id');

        $this->hasMany(
            'Members',
            [
            'foreignKey' => 'role_id'
            ]
        );
        $this->hasMany(
            'View0LineupsDetails',
            [
            'foreignKey' => 'role_id'
            ]
        );
        $this->hasMany(
            'View0Members',
            [
            'foreignKey' => 'role_id'
            ]
        );
        $this->hasMany(
            'View1MembersStats',
            [
            'foreignKey' => 'role_id'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
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
