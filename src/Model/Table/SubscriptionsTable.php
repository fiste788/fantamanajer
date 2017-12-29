<?php

namespace App\Model\Table;

use App\Model\Entity\Subscription;
use App\Model\Table\UsersTable;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Subscriptions Model
 *
 * @property UsersTable|BelongsTo $Users
 *
 * @method Subscription get($primaryKey, $options = [])
 * @method Subscription newEntity($data = null, array $options = [])
 * @method Subscription[] newEntities(array $data, array $options = [])
 * @method Subscription|bool save(EntityInterface $entity, $options = [])
 * @method Subscription patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Subscription[] patchEntities($entities, array $data, array $options = [])
 * @method Subscription findOrCreate($search, callable $callback = null, $options = [])
 */
class SubscriptionsTable extends Table
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

        $this->setTable('subscriptions');
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
            ->integer('id')
            ->allowEmpty('id', 'create');

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
}
