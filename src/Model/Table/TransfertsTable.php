<?php
namespace App\Model\Table;

use App\Model\Entity\Event as Event2;
use ArrayObject;
use Cake\Datasource\EntityInterface;
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
 * @property BelongsTo $Teams
 * @property BelongsTo $Matchdays
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

        $this->table('transferts');
        $this->displayField('id');
        $this->primaryKey('id');

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

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $events = TableRegistry::get('Events');
        $ev = $events->newEntity();
        $ev->type = Event2::NEW_TRANSFERT;
        $ev->team_id = $entity['team_id'];
        $ev->external = $entity['id'];
        $events->save($ev);
    }
}
