<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NotificationSubscriptions Model
 *
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\BelongsTo $Teams
 * @method \App\Model\Entity\NotificationSubscription get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\NotificationSubscription newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\NotificationSubscription[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NotificationSubscription|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\NotificationSubscription saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\NotificationSubscription patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NotificationSubscription[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\NotificationSubscription findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\NotificationSubscription newEmptyEntity()
 * @method \App\Model\Entity\NotificationSubscription[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\NotificationSubscription[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\NotificationSubscription[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\NotificationSubscription[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 */
class NotificationSubscriptionsTable extends Table
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

        $this->setTable('notification_subscriptions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
            'joinType' => 'INNER',
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
            ->scalar('type')
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('name')
            ->maxLength('name', 32)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->boolean('enabled')
            ->notEmptyString('enabled');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     * @throws \Cake\Core\Exception\CakeException If a rule with the same name already exists
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['team_id'], 'Teams'));

        return $rules;
    }
}
