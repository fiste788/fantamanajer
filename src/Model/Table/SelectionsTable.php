<?php

namespace App\Model\Table;

use App\Model\Entity\Selection;
use App\Model\Rule\MemberIsSelectableRule;
use App\Model\Rule\TeamReachedMaxSelectionRule;
use App\Model\Table\MatchdaysTable;
use App\Model\Table\MembersTable;
use App\Model\Table\TeamsTable;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Selections Model
 *
 * @property TeamsTable|BelongsTo $Teams
 * @property BelongsTo $Members
 * @property BelongsTo $Members
 * @property MatchdaysTable|BelongsTo $Matchdays
 * @property MembersTable|BelongsTo $NewMembers
 * @property MembersTable|BelongsTo $OldMembers
 * @method Selection get($primaryKey, $options = [])
 * @method Selection newEntity($data = null, array $options = [])
 * @method Selection[] newEntities(array $data, array $options = [])
 * @method Selection|bool save(EntityInterface $entity, $options = [])
 * @method Selection patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Selection[] patchEntities($entities, array $data, array $options = [])
 * @method Selection findOrCreate($search, callable $callback = null, $options = [])
 * @method Selection|bool saveOrFail(EntityInterface $entity, $options = [])
 */
class SelectionsTable extends Table
{
    use \Burzum\Cake\Service\ServiceAwareTrait;

    public function __construct()
    {
        $this->loadService('Selection');
    }

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('selections');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Teams',
            [
                'foreignKey' => 'team_id',
                'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Matchdays',
            [
                'foreignKey' => 'matchday_id',
                'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'NewMembers',
            [
                'className' => 'Members',
                'foreignKey' => 'old_member_id',
                'propertyName' => 'old_member'
            ]
        );
        $this->belongsTo(
            'OldMembers',
            [
                'className' => 'Members',
                'foreignKey' => 'new_member_id',
                'propertyName' => 'new_member'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        $rules->add($rules->existsIn(['old_member_id'], 'OldMembers'));
        $rules->add($rules->existsIn(['new_member_id'], 'NewMembers'));
        $rules->add(
            new MemberIsSelectableRule(),
            'NewMemberIsSelectable',
            ['errorField' => 'new_member', 'message' => __('The member is already selected by another team')]
        );
        $rules->add(
            new TeamReachedMaxSelectionRule(),
            'TeamReachedMaximum',
            ['errorField' => 'new_member', 'message' => __('Reached maximum number of changes')]
        );

        return $rules;
    }

    /**
     *
     * @param Selection $selection
     * @return Selection
     */
    public function findAlreadySelectedMember($selection)
    {
        $team = $this->Teams->get($selection->team_id);

        return $this->find()
                ->contain(['Teams'])
                ->matching(
                    'Teams',
                    function ($q) use ($team) {
                        return $q->where(['Teams.championship_id' => $team->championship_id]);
                    }
                )
                ->where(
                    [
                        'team_id !=' => $selection->team_id,
                        'new_member_id' => $selection->new_member_id
                    ]
                )->first();
    }

    public function findByTeamIdAndMatchdayId(Query $q, array $options)
    {
        return $q->contain(['Teams', 'OldMembers.Players', 'NewMembers.Players', 'Matchdays'])
            ->where([
                'Selections.active' => true,
                'team_id' => $options['team_id'],
                'matchday_id' => $options['matchday_id'],
                ])->limit(1);
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $event = new Event('Fantamanajer.newMemberSelection', $this, [
                'selection' => $entity
            ]);
            EventManager::instance()->dispatch($event);
        }
    }

    public function beforeSave(Event $event, Selection $entity, ArrayObject $options)
    {
        if ($entity->isDirty('processed') && $entity->processed) {
            $this->Selection->save($entity);
        }
    }
}
