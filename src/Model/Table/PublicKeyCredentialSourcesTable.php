<?php
namespace App\Model\Table;

use Cake\Database\Schema\TableSchema;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PublicKeyCredentialSources Model
 *
 * @method \App\Model\Entity\PublicKeyCredentialSource get($primaryKey, $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PublicKeyCredentialSource findOrCreate($search, callable $callback = null, $options = [])
 */
class PublicKeyCredentialSourcesTable extends Table
{

    /**
     * Undocumented function
     *
     * @param TableSchema $schema Schema
     * @return TableSchema
     */
    protected function _initializeSchema(TableSchema $schema)
    {
        $schema->columnType('trust_path', 'trust_path');
        $schema->columnType('transports', 'simple_array');
        $schema->columnType('aaguid', 'base64');
        $schema->columnType('credential_public_key', 'base64');
        $schema->columnType('public_key_credential_id', 'base64');

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

        $this->addBehavior(
            'Timestamp',
            [
                'events' => [
                    'Model.beforeSave' => [
                        'created_at' => 'new'
                    ]
                ]
            ]
        );

        $this->belongsTo(
            'Users',
            [
                'foreignKey' => 'user_handle',
                'joinType' => 'INNER'
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
            ->scalar('id')
            ->maxLength('id', 100)
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('public_key_credential_id')
            ->maxLength('public_key_credential_id', 4294967295)
            ->requirePresence('public_key_credential_id', 'create')
            ->allowEmptyString('public_key_credential_id', false);

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->allowEmptyString('type', false);

        $validator
            ->scalar('transports')
            ->maxLength('transports', 4294967295)
            ->requirePresence('transports', 'create')
            ->allowEmptyString('transports', false);

        $validator
            ->scalar('attestation_type')
            ->maxLength('attestation_type', 255)
            ->requirePresence('attestation_type', 'create')
            ->allowEmptyString('attestation_type', false);

        $validator
            ->scalar('trust_path')
            ->maxLength('trust_path', 4294967295)
            ->requirePresence('trust_path', 'create')
            ->allowEmptyString('trust_path', false);

        $validator
            ->scalar('aaguid')
            ->maxLength('aaguid', 4294967295)
            ->requirePresence('aaguid', 'create')
            ->allowEmptyString('aaguid', false);

        $validator
            ->scalar('credential_public_key')
            ->maxLength('credential_public_key', 4294967295)
            ->requirePresence('credential_public_key', 'create')
            ->allowEmptyString('credential_public_key', false);

        $validator
            ->scalar('user_handle')
            ->maxLength('user_handle', 255)
            ->requirePresence('user_handle', 'create')
            ->allowEmptyString('user_handle', false);

        $validator
            ->integer('counter')
            ->requirePresence('counter', 'create')
            ->allowEmptyString('counter', false);

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->allowEmptyDateTime('created_at', false);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        return $validator;
    }
}
