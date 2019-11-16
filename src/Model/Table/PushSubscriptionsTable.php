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
     * @inheritDoc
     */
    public function initialize(array $config): void
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
                        'modified_at' => 'always',
                    ],
                ],
            ]
        );

        $this->belongsTo(
            'Users',
            [
                'foreignKey' => 'user_id',
                'joinType' => 'INNER',
            ]
        );
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
     * @param \Cake\Datasource\EntityInterface $entity Entity
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
