<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Override;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany<\App\Model\Table\PublicKeyCredentialSourcesTable> $PublicKeyCredentialSources
 * @property \Cake\ORM\Association\HasMany<\App\Model\Table\PushSubscriptionsTable> $PushSubscriptions
 * @property \Cake\ORM\Association\HasMany<\App\Model\Table\TeamsTable> $Teams
 * @method \App\Model\Entity\User get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\User newEntity(array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\User> newEntities(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\User> patchEntities(iterable<\Cake\Datasource\EntityInterface> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\User findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array<string, mixed> $options = [])
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>|false saveMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\User> saveManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>|false deleteMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\User> deleteManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    #[Override]
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
            //'sort' => 'Championships.id DESC',
            'finder' => 'teamsOrdered',
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
     * @throws \Cake\Core\Exception\CakeException If a rule with the same name already exists
     */
    #[Override]
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['username']));

        return $rules;
    }

    /**
     * Finder for identity
     *
     * @param \Cake\ORM\Query\SelectQuery $query query
     * @param mixed ...$args
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findAuth(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query
            ->contain(['Teams' => function (SelectQuery $q): SelectQuery {
                return $q->select(['id', 'name', 'photo', 'photo_dir', 'user_id', 'championship_id'])
                    ->contain([
                        'Championships' => function (SelectQuery $q): SelectQuery {
                            return $q->select([
                                'id',
                                'league_id',
                                'season_id',
                                'jolly',
                                'captain',
                                'number_benchwarmers',
                                'started',
                            ])->select($this->Teams->Championships->Leagues)
                                ->select($this->Teams->Championships->Seasons)
                                ->contain([
                                    'Leagues',
                                    'Seasons',
                                ]);
                        },
                    ]);
            }])
            ->where(['Users.active' => 1]);
    }
}
