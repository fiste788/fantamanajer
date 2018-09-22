<?php

namespace App\Model\Table;

use App\Model\Entity\Selection;
use App\Model\Table\MatchdaysTable;
use App\Model\Table\MembersTable;
use App\Model\Table\TeamsTable;
use App\Utility\WebPush\WebPushMessage;
use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Mailer\Email;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Minishlink\WebPush\WebPush;

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
        $that = $this;
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        $rules->add($rules->existsIn(['old_member_id'], 'OldMembers'));
        $rules->add($rules->existsIn(['new_member_id'], 'NewMembers'));
        $rules->add(
            function (Selection $entity, $options) {
                $selection = $this->findAlreadySelectedMember($entity);
                if ($selection != null) {
                    $ranking = TableRegistry::get('Scores')->find('ranking', [
                        'championship_id' => $selection->team->championship_id
                    ]);
                    $rank = Hash::extract($ranking->toArray(), '{n}.team_id');
                    if (array_search($entity->team_id, $rank) > array_search($selection->team->id, $rank)) {
                        $selection->active = false;
                        $this->save($selection);
                        $this->notifyLostMember($selection);

                        return true;
                    } else {
                        return false;
                    }
                }

                return true;
            },
            'NewMemberIsSelectable',
            ['errorField' => 'new_member', 'message' => __('The member is selected by another team')]
        );
        $rules->add(
            function (Selection $entity, $options) use ($that) {
                $championship = TableRegistry::get('Championships')->find()->innerJoinWith(
                    'Teams',
                    function ($q) use ($entity) {
                        return $q->where(['Teams.id' => $entity->team_id]);
                    }
                )->first();

                return $that->find()
                    ->where(
                        [
                            'matchday_id' => $entity->matchday_id,
                            'team_id' => $entity->team_id,
                            'processed' => false
                        ]
                    )->count() < $championship->number_selections;
            },
            'TeamReachedMaximum',
            ['errorField' => 'new_member', 'message' => __('Reached maximum number of changes')]
        );

        return $rules;
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
            $membersTeamsTable = TableRegistry::get('MembersTeams');
            $memberTeam = $membersTeamsTable->find()
                ->contain(['Members'])
                ->where([
                    'team_id' => $entity->team_id,
                    'member_id' => $entity->old_member_id
                ])->first();
            $memberTeam->member_id = $entity->new_member_id;
            $membersTeamsTable->save($memberTeam);
        }
    }

    /**
     *
     * @param Selection $selection
     */
    public function notifyLostMember(Selection $selection)
    {
        $selection = $this->loadInto($selection, ['Teams' => [
            'EmailNotificationSubscriptions',
            'PushNotificationSubscriptions',
            'Users.Subscriptions'
        ], 'NewMembers.Players']);
        if ($selection->team->isEmailSubscripted('lost_member')) {
            $email = new Email();
            $email->setTemplate('lost_member')
                ->setViewVars(
                    [
                        'player' => $selection->new_member->player,
                        'baseUrl' => 'https://fantamanajer.it'
                    ]
                )
                ->setSubject('Un altra squadra ti ha soffiato un giocatore selezionato')
                ->setEmailFormat('html')
                ->setTo($selection->team->user->email)
                ->send();
        }
        if ($selection->team->isPushSubscripted('lost_member')) {
            $webPush = new WebPush(Configure::read('WebPush'));
            foreach ($selection->team->user->subscriptions as $subscription) {
                $message = WebPushMessage::create(Configure::read('WebPushMessage.default'))
                    ->title('Un altra squadra ti ha soffiato un giocatore selezionato')
                    ->body('Hai perso il giocatore ' . $selection->new_member->player->surname . ' ' . $selection->new_member->player->name)
                    ->tag('lost-player-' . $selection->id);
                $webPush->sendNotification($subscription->getSubscription(), json_encode($message));
            }
        }
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
                'team_id' => $options['team_id'],
                'matchday_id' => $options['matchday_id'],
                ])->limit(1);
    }
}
