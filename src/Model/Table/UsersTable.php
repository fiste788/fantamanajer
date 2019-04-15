<?php

namespace App\Model\Table;

use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property TeamsTable|\Cake\ORM\Association\HasMany $Teams
 * @property PushSubscriptionsTable|\Cake\ORM\Association\HasMany $PushSubscriptions
 * @property CredentialsTable|\Cake\ORM\Association\HasMany $Credentials
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany(
            'PublicKeyCredentialSources',
            [
                'bindingKey' => 'uuid',
                'foreignKey' => 'user_handle',
                'sort' => 'created_at'
            ]
        );
        $this->hasMany(
            'Teams',
            [
                'foreignKey' => 'user_id',
                'sort' => 'Championships.id DESC'
            ]
        );
        $this->hasMany(
            'PushSubscriptions',
            [
                'foreignKey' => 'user_id',
                'sort' => 'modified_at DESC'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
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
            ->requirePresence('email')
            ->email('email')
            ->notEmpty('email');

        $validator
            ->boolean('active_email')
            ->requirePresence('active_email', 'create')
            ->notEmpty('active_email');

        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        $validator
            ->allowEmpty('username');

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
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['username']));

        return $rules;
    }

    /**
     * Finder for identity
     *
     * @param Query $query query
     * @param array $options options
     * @return Query
     */
    public function findAuth(Query $query, array $options)
    {
        $query
            ->contain(['Teams' => ['Championships' => ['Leagues', 'Seasons']]])
            ->where(['Users.active' => 1]);

        return $query;
    }
}
