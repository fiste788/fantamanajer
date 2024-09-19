<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Transfert;
use ArrayObject;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transferts Model
 *
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $NewMembers
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $OldMembers
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\BelongsTo $Teams
 * @property \App\Model\Table\MatchdaysTable&\Cake\ORM\Association\BelongsTo $Matchdays
 * @property \App\Service\TransfertService $Transfert
 * @method \App\Model\Entity\Transfert get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Transfert newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Transfert[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Transfert|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Transfert saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Transfert patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Transfert[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Transfert findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Transfert newEmptyEntity()
 * @method \App\Model\Entity\Transfert[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Transfert[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Transfert[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Transfert[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 */
class TransfertsTable extends Table
{
    use ServiceAwareTrait;

    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('transferts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('NewMembers', [
            'className' => 'Members',
            'foreignKey' => 'new_member_id',
            'propertyName' => 'new_member',
        ]);
        $this->belongsTo('OldMembers', [
            'className' => 'Members',
            'foreignKey' => 'old_member_id',
            'propertyName' => 'old_member',
        ]);
        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Matchdays', [
            'foreignKey' => 'matchday_id',
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
            ->boolean('constrained')
            ->requirePresence('constrained', 'create')
            ->notEmptyString('constrained');

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
        $rules->add($rules->existsIn(['new_member_id'], 'NewMembers'));
        $rules->add($rules->existsIn(['old_member_id'], 'OldMembers'));
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));

        return $rules;
    }

    /**
     * Before marshal
     *
     * @param \Cake\Event\Event $event Event
     * @param \ArrayObject $data Data
     * @param \ArrayObject $options Options
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options): void
    {
        /** @var \App\Model\Entity\Matchday $current */
        $current = $this->Matchdays->find('current')->first();
        $data['matchday_id'] = $current->id;
    }

    /**
     * Find by team id
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed $args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByTeamId(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->contain(['OldMembers.Players', 'NewMembers.Players', 'Matchdays'])
            ->where(['team_id' => $args['team_id']]);
    }

    /**
     * Before save event
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\Transfert $entity Entity
     * @param \ArrayObject $options Options
     * @return void
     */
    public function beforeSave(Event $event, Transfert $entity, ArrayObject $options): void
    {
        /** @var \App\Model\Entity\Matchday $current */
        $current = $this->Matchdays->find('current')->first();
        $entity->matchday_id = $current->id;
    }

    /**
     * After save event
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\Transfert $entity Entity
     * @param \ArrayObject $options Options
     * @return void
     * @throws \Cake\Core\Exception\CakeException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function afterSave(Event $event, Transfert $entity, ArrayObject $options): void
    {
        EventManager::instance()->dispatch(new Event('Fantamanajer.newMemberTransfert', $this, [
            'transfert' => $entity,
        ]));

        $this->loadService('Transfert');
        $this->Transfert->substituteMemberInLineup($entity);
    }
}
