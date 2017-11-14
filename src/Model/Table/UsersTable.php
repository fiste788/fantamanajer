<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $Teams
 * @property \Cake\ORM\Association\HasMany $Subscriptions
 * @property \Cake\ORM\Association\HasMany $View2TeamsStats
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Teams', [
            'foreignKey' => 'user_id',
            'sort' => 'Championships.id DESC'
        ]);
        $this->hasMany('Subscriptions', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('View2TeamsStats', [
            'foreignKey' => 'user_id'
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('name');

        $validator
            ->requirePresence('surname', 'create')
            ->notEmpty('surname');

        $validator
            ->email('email')
            ->allowEmpty('email');

        $validator
            ->boolean('active_email')
            ->requirePresence('active_email', 'create')
            ->notEmpty('active_email');
        
        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        $validator
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->allowEmpty('login_key');

        $validator
            ->boolean('admin')
            ->requirePresence('admin', 'create')
            ->notEmpty('admin');

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['username']));
        return $rules;
    }
    
    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        $query
            ->contain(['Teams' => ['Championships' => ['Leagues','Seasons']]])
            ->where(['Users.active' => 1]);

        return $query;
    }
}
