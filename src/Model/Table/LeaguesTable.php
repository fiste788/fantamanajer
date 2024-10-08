<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Leagues Model
 *
 * @property \App\Model\Table\ChampionshipsTable&\Cake\ORM\Association\HasMany $Championships
 * @method \App\Model\Entity\League get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\League newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\League[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\League|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\League saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\League patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\League[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\League findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\League newEmptyEntity()
 * @method \App\Model\Entity\League[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\League[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\League[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\League[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 */
class LeaguesTable extends Table
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

        $this->setTable('leagues');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Championships', [
            'foreignKey' => 'league_id',
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
            ->scalar('name')
            ->minLength('name', 3)
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
}
