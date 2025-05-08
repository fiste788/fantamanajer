<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Override;

/**
 * MembersStats Model
 *
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\TeamsTable> $Teams
 * @method \App\Model\Entity\RollOfHonor get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\RollOfHonor newEntity(array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\RollOfHonor> newEntities(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\RollOfHonor|false save(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\RollOfHonor patchEntity(\Cake\Datasource\EntityInterface $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\RollOfHonor> patchEntities(iterable<\Cake\Datasource\EntityInterface> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\RollOfHonor findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array<string, mixed> $options = [])
 * @method \App\Model\Entity\RollOfHonor saveOrFail(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\RollOfHonor newEmptyEntity()
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\RollOfHonor>|false saveMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\RollOfHonor> saveManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\RollOfHonor>|false deleteMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\RollOfHonor> deleteManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\LeaguesTable> $Leagues
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\ChampionshipsTable> $Championships
 */
class RollOfHonorsTable extends Table
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $schema = $this->getSchema();

        $schema->setColumnType('points', 'decimal');
        $schema->setColumnType('rank', 'integer');

        $this->setTable('roll_of_honors');

        $this->belongsTo(
            'Leagues',
            [
                'foreignKey' => 'league_id',
                'joinType' => 'INNER',
            ],
        );

        $this->belongsTo(
            'Championships',
            [
                'foreignKey' => 'championship_id',
                'joinType' => 'INNER',
            ],
        );

        $this->belongsTo(
            'Teams',
            [
                'foreignKey' => 'team_id',
                'joinType' => 'INNER',
            ],
        );
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
            ->numeric('points')
            ->allowEmptyString('points');

        $validator
            ->integer('rank')
            ->allowEmptyString('rank');

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
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['championship_id'], 'Championships'));
        $rules->add($rules->existsIn(['league_id'], 'Leagues'));

        return $rules;
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Database\Schema\TableSchemaInterface $schema Schema
     * @return \Cake\Database\Schema\TableSchemaInterface
     */
    protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface
    {
        $schema->setColumnType('points', 'float');
        $schema->setColumnType('rank', 'integer');

        return $schema;
    }
}
