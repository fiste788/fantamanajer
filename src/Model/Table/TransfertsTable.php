<?php

namespace App\Model\Table;

use App\Model\Entity\Transfert;
use ArrayObject;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transferts Model
 *
 * @property BelongsTo $Members
 * @property BelongsTo $Members
 * @property TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $NewMembers
 * @property MembersTable|\Cake\ORM\Association\BelongsTo $OldMembers
 * @method \App\Model\Entity\Transfert get($primaryKey, $options = [])
 * @method \App\Model\Entity\Transfert newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Transfert[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Transfert|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transfert patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Transfert[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Transfert findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Transfert|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class TransfertsTable extends Table
{

    use \Burzum\Cake\Service\ServiceAwareTrait;

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('transferts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->boolean('constrained')
            ->allowEmpty('constrained', 'create');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['old_member_id'], 'OldMembers'));
        $rules->add($rules->existsIn(['new_member_id'], 'NewMembers'));
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));

        return $rules;
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options): void
    {
        $data['matchday_id'] = $this->Matchdays->find('current')->first()->id;
    }

    public function findByTeamId(Query $q, array $options): Query
    {
        return $q->contain(['OldMembers.Players', 'NewMembers.Players', 'Matchdays'])
            ->where(['team_id' => $options['team_id']]);
    }

    public function beforeSave(Event $event, Transfert $entity, ArrayObject $options): void
    {
        $entity->matchday_id = $this->Matchdays->find('current')->first()->id;
    }

    public function afterSave(Event $event, Transfert $entity, ArrayObject $options): void
    {
        EventManager::instance()->dispatch(new Event('Fantamanajer.newMemberTransfert', $this, [
            'transfert' => $entity
        ]));

        $this->loadService('Transfert');
        $this->Transfert->substituteMemberInLineup($entity);
    }
}
