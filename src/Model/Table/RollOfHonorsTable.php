<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MembersStats Model
 *
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\BelongsTo $Teams
 * @method \App\Model\Entity\MembersStat get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\MembersStat newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MembersStat[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MembersStat|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\MembersStat patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MembersStat[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MembersStat findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\MembersStat saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\MembersStat newEmptyEntity()
 * @method \App\Model\Entity\MembersStat[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\MembersStat[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\MembersStat[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\MembersStat[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 */
class RollOfHonorsTable extends Table
{
    /**
     * @inheritDoc
     */
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
            ]
        );

        $this->belongsTo(
            'Championships',
            [
                'foreignKey' => 'championship_id',
                'joinType' => 'INNER',
            ]
        );

        $this->belongsTo(
            'Teams',
            [
                'foreignKey' => 'team_id',
                'joinType' => 'INNER',
            ]
        );
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
