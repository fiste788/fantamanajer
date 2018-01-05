<?php
namespace App\Model\Table;

use App\Model\Entity\Event;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event as CakeEvent;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Selections Model
 *
 * @property \App\Model\Table\TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property BelongsTo $Members
 * @property BelongsTo $Members
 * @property \App\Model\Table\MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $NewMembers
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $OldMembers
 * @method \App\Model\Entity\Selection get($primaryKey, $options = [])
 * @method \App\Model\Entity\Selection newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Selection[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Selection|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Selection patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Selection[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Selection findOrCreate($search, callable $callback = null, $options = [])
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
            function (\App\Model\Entity\Selection $entity, $options) {
                if ($entity->isMemberAlreadySelected()) {
                    $ranking = TableRegistry::get('Scores')->findRankingByChampionshipId($entity->team->championship_id);

                    return $ranking[$entity->team_id] < $ranking[$this->team_id];
                }

                return true;
            },
            'NewMemberIsSelectable',
            ['errorField' => 'new_member', 'message' => 'Un altro utente ha già selezionato il giocatore']
        );
        $rules->add(
            function (\App\Model\Entity\Selection $entity, $options) use ($that) {
                $championship = TableRegistry::get('Championships')->find()->innerJoinWith(
                    'Teams',
                    function ($q) use ($entity) {
                        return $q->where(['Teams.id' => $entity->team_id]);
                    }
                )->first();

                return $that->find()->where(['team_id' => $entity->team_id, 'processed' => false])->count() < $championship->number_selections;
            },
            'TeamReachedMaximum',
            ['errorField' => 'new_member', 'message' => 'Hai raggiunto il limite di cambi selezione']
        );

        return $rules;
    }

    public function afterSave(CakeEvent $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            $events = TableRegistry::get('Events');
            $ev = $events->newEntity();
            $ev->type = Event::NEW_PLAYER_SELECTION;
            $ev->team_id = $entity['team_id'];
            $events->save($ev);
        }
    }

    public function beforeSave(CakeEvent $event, \App\Model\Entity\Selection $entity, ArrayObject $options)
    {
        if ($entity->dirty('processed') && $entity->processed) {
            $membersTeamsTable = TableRegistry::get('MembersTeams');
            $transfertsTable = TableRegistry::get('Transferts');
            $memberTeam = $membersTeamsTable->find()
                ->where(
                    [
                        'team_id' => $entity->team_id,
                        'member_id' => $entity->old_member_id
                        ]
                )
                ->first();
            $memberTeam->member_id = $entity->new_member_id;
            $transfert = $entity->toTransfert($transfertsTable);
            $membersTeamsTable->save($memberTeam);
            $transfertsTable->save($transfert);
        }
    }
}
