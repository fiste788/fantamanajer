<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Selection;
use App\Model\Rule\MemberIsSelectableRule;
use App\Model\Rule\TeamReachedMaxSelectionRule;
use ArrayObject;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Override;

/**
 * Selections Model
 *
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\TeamsTable> $Teams
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\MatchdaysTable> $Matchdays
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\MembersTable> $NewMembers
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\MembersTable> $OldMembers
 * @property \App\Service\SelectionService $Selection
 * @method \App\Model\Entity\Selection get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Selection newEntity(array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\Selection> newEntities(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Selection|false save(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Selection saveOrFail(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Selection patchEntity(\Cake\Datasource\EntityInterface $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method array<\App\Model\Entity\Selection> patchEntities(iterable<\Cake\Datasource\EntityInterface> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Selection findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Selection newEmptyEntity()
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Selection>|false saveMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Selection> saveManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Selection>|false deleteMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Selection> deleteManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @extends \Cake\ORM\Table<array{Timestamp: \Cake\ORM\Behavior\TimestampBehavior}>
 */
class SelectionsTable extends Table
{
    use ServiceAwareTrait;

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

        $this->setTable('selections');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Matchdays', [
            'foreignKey' => 'matchday_id',
            'joinType' => 'INNER',
        ]);
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
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'modified_at' => 'always',
                ],
            ],
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
            ->boolean('active')
            ->notEmptyString('active');

        $validator
            ->boolean('processed')
            ->notEmptyString('processed');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('modified_at')
            ->allowEmptyDateTime('modified_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    #[Override]
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        $rules->add($rules->existsIn(['old_member_id'], 'OldMembers'));
        $rules->add($rules->existsIn(['new_member_id'], 'NewMembers'));
        $rules->addCreate(new MemberIsSelectableRule(), 'NewMemberIsSelectable', [
            'errorField' => 'new_member',
            'message' => __('The member is already selected by another team'),
        ]);
        $rules->addCreate(new TeamReachedMaxSelectionRule(), 'TeamReachedMaximum', [
            'errorField' => 'new_member',
            'message' => __('Reached maximum number of changes'),
        ]);

        return $rules;
    }

    /**
     * @inheritDoc
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options): void
    {
        if ($data->offsetExists('created_at')) {
            $data->offsetUnset('created_at');
        }
        if ($data->offsetExists('modified_at')) {
            $data->offsetUnset('modified_at');
        }
    }

    /**
     * @param \App\Model\Entity\Selection $selection Selection
     * @return \App\Model\Entity\Selection|null
     * @throws \Cake\Datasource\Exception\InvalidPrimaryKeyException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function findAlreadySelectedMember(Selection $selection): ?Selection
    {
        $team = $this->Teams->get($selection->team_id);

        /** @var \App\Model\Entity\Selection|null $selection */
        $selection = $this->find()
            ->contain(['Teams'])
            ->matching(
                'Teams',
                function (SelectQuery $q) use ($team): SelectQuery {
                    return $q->where(['Teams.championship_id' => $team->championship_id]);
                },
            )
            ->where(
                [
                    'team_id !=' => $selection->team_id,
                    'new_member_id' => $selection->new_member_id,
                ],
            )->first();

        return $selection;
    }

    /**
     * Find by team and matchday query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery Query
     */
    public function findByTeamIdAndMatchdayId(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->contain(['Teams', 'OldMembers.Players', 'NewMembers.Players', 'Matchdays'])
            ->where([
                'Selections.active' => true,
                'team_id' => $args['team_id'],
                'matchday_id' => $args['matchday_id'],
            ])->limit(1);
    }

    /**
     * After save event
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\Selection $entity Entity
     * @param \ArrayObject $options Options
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew()) {
            $event = new Event('Fantamanajer.newMemberSelection', $this, [
                'selection' => $entity,
            ]);
            EventManager::instance()->dispatch($event);
        }
    }

    /**
     * Before save event
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\Selection $entity Entity
     * @param \ArrayObject $options Options
     * @throws \Cake\Core\Exception\CakeException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @return void
     */
    public function beforeSave(Event $event, Selection $entity, ArrayObject $options): void
    {
        if ($entity->isDirty('processed') && $entity->processed) {
            $this->loadService('Selection');

            $this->Selection->save($entity);
        }
    }
}
