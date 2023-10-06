<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Clubs Model
 *
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\HasMany $Members
 * @method \App\Model\Entity\Club get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Club newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Club[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Club|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Club saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Club patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Club[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Club findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Club newEmptyEntity()
 * @method \App\Model\Entity\Club[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Club[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Club[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Club[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ClubsTable extends Table
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

        $this->setTable('clubs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Members', [
            'foreignKey' => 'club_id',
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
            ->maxLength('name', 15)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('partitive')
            ->maxLength('partitive', 10)
            ->notEmptyString('partitive');

        $validator
            ->scalar('determinant')
            ->maxLength('determinant', 3)
            ->notEmptyString('determinant');

        return $validator;
    }

    /**
     * Find by season query
     *
     * @param \Cake\ORM\Query\SelectQuery $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findBySeasonId(SelectQuery $q, array $options): SelectQuery
    {
        return $q->innerJoinWith('Members', function (SelectQuery $q) use ($options): SelectQuery {
            return $q->where(['season_id' => $options['season_id']]);
        })->groupBy('Clubs.id')
            ->orderByAsc('name');
    }
}
