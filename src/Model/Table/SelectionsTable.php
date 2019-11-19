<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Selection;
use App\Model\Rule\MemberIsSelectableRule;
use App\Model\Rule\TeamReachedMaxSelectionRule;
use ArrayObject;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Selections Model
 *
 * @property \App\Service\SelectionService $Selection
 * @property \App\Model\Table\TeamsTable&\App\Model\Table\BelongsTo $Teams
 * @property \Cake\ORM\Association\BelongsTo $Members
 * @property \App\Model\Table\MatchdaysTable&\App\Model\Table\BelongsTo $Matchdays
 * @property \App\Model\Table\MembersTable&\App\Model\Table\BelongsTo $NewMembers
 * @property \App\Model\Table\MembersTable&\App\Model\Table\BelongsTo $OldMembers
 * @method \App\Model\Entity\Selection get($primaryKey, $options = [])
 * @method \App\Model\Entity\Selection newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Selection[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Selection|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Selection patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Selection[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Selection findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Selection|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class SelectionsTable extends Table
{
    use ServiceAwareTrait;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadService('Selection');
    }

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('selections');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Teams',
            [
                'foreignKey' => 'team_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Matchdays',
            [
                'foreignKey' => 'matchday_id',
                'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'NewMembers',
            [
                'className' => 'Members',
                'foreignKey' => 'old_member_id',
                'propertyName' => 'old_member',
            ]
        );
        $this->belongsTo(
            'OldMembers',
            [
                'className' => 'Members',
                'foreignKey' => 'new_member_id',
                'propertyName' => 'new_member',
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
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
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
     * @param \App\Model\Entity\Selection $selection Selection
     * @return \App\Model\Entity\Selection
     */
    public function findAlreadySelectedMember(Selection $selection): Selection
    {
        $team = $this->Teams->get($selection->team_id);

        /** @var \App\Model\Entity\Selection $selection */
        $selection = $this->find()
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
                    'new_member_id' => $selection->new_member_id,
                ]
            )->first();

        return $selection;
    }

    /**
     * Find by team and matchday query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query Query
     */
    public function findByTeamIdAndMatchdayId(Query $q, array $options): Query
    {
        return $q->contain(['Teams', 'OldMembers.Players', 'NewMembers.Players', 'Matchdays'])
            ->where([
                'Selections.active' => true,
                'team_id' => $options['team_id'],
                'matchday_id' => $options['matchday_id'],
            ])->limit(1);
    }

    /**
     * After save event
     *
     * @param \Cake\Event\Event $event Event
     * @param \Cake\Datasource\EntityInterface $entity Entity
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
     * @return void
     */
    public function beforeSave(Event $event, Selection $entity, ArrayObject $options): void
    {
        if ($entity->isDirty('processed') && $entity->processed) {
            $this->Selection->save($entity);
        }
    }
}
