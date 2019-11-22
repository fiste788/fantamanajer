<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PublicKeyCredentialSources Model
 *
 * @method \App\Model\Entity\PublicKeyCredentialSource get($primaryKey, $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource findOrCreate($search, callable $callback = null, $options = [])
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PublicKeyCredentialSourcesTable extends Table
{
    /**
     * @inheritDoc
     */
    protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface
    {
        $schema->setColumnType('trust_path', 'trust_path');
        $schema->setColumnType('transports', 'simple_array');
        $schema->setColumnType('aaguid', 'base64');
        $schema->setColumnType('credential_public_key', 'base64');
        $schema->setColumnType('public_key_credential_id', 'base64');

        return $schema;
    }

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
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
     */
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
}
