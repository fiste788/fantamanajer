<?php

namespace App\Model\Table;

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PushSubscriptions Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\PushSubscription get($primaryKey, $options = [])
 * @method \App\Model\Entity\PushSubscription newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PushSubscription[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PushSubscription|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PushSubscription patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PushSubscription[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PushSubscription findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\PushSubscription|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class PushSubscriptionsTable extends Table
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

        $this->setTable('push_subscriptions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior(
            'Timestamp',
            [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'modified_at' => 'always'
                ]
            ]
            ]
        );

        $this->belongsTo(
            'Users',
            [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->alphaNumeric('id')
            ->requirePresence('id', 'create');

        $validator
            ->scalar('endpoint')
            ->requirePresence('endpoint', 'create')
            ->notEmpty('endpoint')
            ->add('endpoint', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('public_key')
            ->allowEmpty('public_key');

        $validator
            ->scalar('auth_token')
            ->allowEmpty('auth_token');

        $validator
            ->allowEmpty('expires_at');

        return $validator;
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data['created_at'])) {
            unset($data['created_at']);
        }
        if (isset($data['modified_at'])) {
            unset($data['modified_at']);
        }
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['endpoint']));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $entity->setId(\Cake\Utility\Security::hash($entity->endpoint, 'sha256'));
        }
    }
}
