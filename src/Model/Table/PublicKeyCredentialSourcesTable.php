<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Override;

/**
 * PublicKeyCredentialSources Model
 *
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\UsersTable> $Users
 * @method \App\Model\Entity\PublicKeyCredentialSource get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PublicKeyCredentialSource newEntity(array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\PublicKeyCredentialSource> newEntities(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource|false save(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource saveOrFail(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource patchEntity(\Cake\Datasource\EntityInterface $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\PublicKeyCredentialSource> patchEntities(iterable<\Cake\Datasource\EntityInterface> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array<string, mixed> $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource newEmptyEntity()
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\PublicKeyCredentialSource>|false saveMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\PublicKeyCredentialSource> saveManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\PublicKeyCredentialSource>|false deleteMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\PublicKeyCredentialSource> deleteManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @extends \Cake\ORM\Table<array{Timestamp: \Cake\ORM\Behavior\TimestampBehavior}>
 */
class PublicKeyCredentialSourcesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     * @throws \RuntimeException
     */
    #[Override]
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('public_key_credential_sources');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                ],
            ],
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_handle',
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
    #[Override]
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('id')
            ->maxLength('id', 100)
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('public_key_credential_id')
            ->requirePresence('public_key_credential_id', 'create')
            ->notEmptyString('public_key_credential_id');

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->allowEmptyString('type');

        $validator
            ->scalar('transports')
            ->requirePresence('transports', 'create')
            ->notEmptyString('transports');

        $validator
            ->scalar('attestation_type')
            ->maxLength('attestation_type', 255)
            ->requirePresence('attestation_type', 'create')
            ->notEmptyString('attestation_type');

        $validator
            ->scalar('trust_path')
            ->requirePresence('trust_path', 'create')
            ->notEmptyString('trust_path');

        $validator
            ->scalar('aaguid')
            ->requirePresence('aaguid', 'create')
            ->notEmptyString('aaguid');

        $validator
            ->scalar('credential_public_key')
            ->requirePresence('credential_public_key', 'create')
            ->notEmptyString('credential_public_key');

        $validator
            ->scalar('user_handle')
            ->maxLength('user_handle', 255)
            ->requirePresence('user_handle', 'create')
            ->notEmptyString('user_handle');

        $validator
            ->integer('counter')
            ->requirePresence('counter', 'create')
            ->notEmptyString('counter');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->scalar('user_agent')
            ->maxLength('user_agent', 255)
            ->allowEmptyString('user_agent');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmptyDateTime('created_at');

        return $validator;
    }

    /**
     * Find by member id  query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByUuid(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->where(['user_handle' => $args['uuid']]);
    }
}
