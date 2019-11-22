<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\PublicKeyCredentialSourcesTable&\Cake\ORM\Association\HasMany $PublicKeyCredentialSources
 * @property \App\Model\Table\PushSubscriptionsTable&\Cake\ORM\Association\HasMany $PushSubscriptions
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\HasMany $Teams
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('PublicKeyCredentialSources', [
            'bindingKey' => 'uuid',
            'foreignKey' => 'user_handle',
            'sort' => 'created_at DESC',
        ]);
        $this->hasMany('PushSubscriptions', [
            'foreignKey' => 'user_id',
            'sort' => 'modified_at DESC',
        ]);
        $this->hasMany('Teams', [
            'foreignKey' => 'user_id',
            'sort' => 'Championships.id DESC',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->allowEmptyString('uuid');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('name')
            ->maxLength('name', 32)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('surname')
            ->maxLength('surname', 32)
            ->requirePresence('surname', 'create')
            ->notEmptyString('surname');

        $validator
            ->scalar('username')
            ->maxLength('username', 50)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('login_key')
            ->maxLength('login_key', 35)
            ->allowEmptyString('login_key');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        $validator
            ->boolean('admin')
            ->notEmptyString('admin');

        $validator
            ->boolean('active_email')
            ->notEmptyString('active_email');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
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
     * @param \Cake\ORM\Query $query query
     * @param array $options options
     * @return \Cake\ORM\Query
     */
    public function findAuth(Query $query, array $options): Query
    {
        $query
            ->contain(['Teams' => ['Championships' => ['Leagues', 'Seasons']]])
            ->where(['Users.active' => 1]);

        return $query;
    }
}
