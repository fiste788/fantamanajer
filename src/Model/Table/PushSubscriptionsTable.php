<?php

declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Security;
use Cake\Validation\Validator;

/**
 * PushSubscriptions Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\PushSubscription get($primaryKey, $options = [])
 * @method \App\Model\Entity\PushSubscription newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PushSubscription[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PushSubscription|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PushSubscription saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PushSubscription patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PushSubscription[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PushSubscription findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PushSubscriptionsTable extends Table
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

        $this->setTable('push_subscriptions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'modified_at' => 'always',
                ],
            ],
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
            ->scalar('id')
            ->maxLength('id', 64)
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('endpoint')
            ->maxLength('endpoint', 255)
            ->requirePresence('endpoint', 'create')
            ->notEmptyString('endpoint')
            ->add('endpoint', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('public_key')
            ->maxLength('public_key', 255)
            ->requirePresence('public_key', 'create')
            ->notEmptyString('public_key');

        $validator
            ->scalar('auth_token')
            ->maxLength('auth_token', 255)
            ->requirePresence('auth_token', 'create')
            ->notEmptyString('auth_token');

        $validator
            ->scalar('content_encoding')
            ->maxLength('content_encoding', 32)
            ->allowEmptyString('content_encoding');

        $validator
            ->dateTime('expires_at')
            ->notEmptyDateTime('expires_at');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('modified_at')
            ->allowEmptyDateTime('modified_at');

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
        $rules->add($rules->isUnique(['endpoint']));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * Before marshal event
     *
     * @param \Cake\Event\Event $event Event
     * @param \ArrayObject $data Data
     * @param \ArrayObject $options Options
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        $data->offsetUnset('created_at');
        $data->offsetUnset('modified_at');
    }

    /**
     * Before save event
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\PushSubscription $entity Entity
     * @param \ArrayObject $options Options
     * @return void
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew()) {
            $entity->id = Security::hash($entity->offsetGet('endpoint'), 'sha256');
        }
    }
}
