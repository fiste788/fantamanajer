<?php
namespace App\Model\Table;

use App\Model\Entity\Event as Event2;
use App\Model\Entity\Transfert;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Transferts Model
 *
 * @property BelongsTo $Members
 * @property BelongsTo $Members
 * @property \App\Model\Table\TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property \App\Model\Table\MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $NewMembers
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $OldMembers
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

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->boolean('constrained')
            ->requirePresence('constrained', 'create')
            ->notEmpty('constrained');

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
        $rules->add($rules->existsIn(['old_member_id'], 'OldMembers'));
        $rules->add($rules->existsIn(['new_member_id'], 'NewMembers'));
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));

        return $rules;
    }

    public function afterSave(Event $event, Transfert $entity, ArrayObject $options)
    {
        $events = TableRegistry::get('Events');
        $ev = $events->newEntity();
        $ev->type = Event2::NEW_TRANSFERT;
        $ev->team_id = $entity->team_id;
        $ev->external = $entity->id;
        $events->save($ev);

        $lineups = TableRegistry::get('Lineups');
        $lineup = $lineups->find()
            ->contain(['Dispositions'])
            ->where(['team_id' => $entity->team_id, 'matchday_id' => $entity->matchday_id])
            ->first();
        if ($lineup && $lineup->substitute($entity->old_member_id, $entity->new_member_id)) {
            $lineups->save($lineup, true);
        }
    }
}
