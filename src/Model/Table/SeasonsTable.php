<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Seasons Model
 *
 * @property \App\Model\Table\ChampionshipsTable&\Cake\ORM\Association\HasMany $Championships
 * @property \App\Model\Table\MatchdaysTable&\Cake\ORM\Association\HasMany $Matchdays
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\HasMany $Members
 * @method \App\Model\Entity\Season get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Season newEntity(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Season[] newEntities(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Season|false save(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Season saveOrFail(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Season patchEntity(\Cake\Datasource\EntityInterface $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Season[] patchEntities(iterable<\Cake\Datasource\EntityInterface> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Season findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Season newEmptyEntity()
 * @method \App\Model\Entity\Season[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Season>|false saveMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Season[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Season> saveManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Season[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Season>|false deleteMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Season[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Season> deleteManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 */
class SeasonsTable extends Table
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

        $this->setTable('seasons');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Championships', [
            'foreignKey' => 'season_id',
        ]);
        $this->hasMany('Matchdays', [
            'foreignKey' => 'season_id',
        ]);
        $this->hasMany('Members', [
            'foreignKey' => 'season_id',
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
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('year')
            ->requirePresence('year', 'create')
            ->notEmptyString('year');

        $validator
            ->scalar('key_gazzetta')
            ->maxLength('key_gazzetta', 255)
            ->allowEmptyString('key_gazzetta');

        $validator
            ->boolean('bonus_points_goals')
            ->notEmptyString('bonus_points_goals');

        $validator
            ->boolean('bonus_points_clean_sheet')
            ->notEmptyString('bonus_points_clean_sheet');

        return $validator;
    }
}
