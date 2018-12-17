<?php
namespace App\Model\Table;

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * MembersTeams Model
 *
 * @property \App\Model\Table\TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $Members
 * @method \App\Model\Entity\MembersTeam get($primaryKey, $options = [])
 * @method \App\Model\Entity\MembersTeam newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MembersTeam[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MembersTeam|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MembersTeam patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MembersTeam[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MembersTeam findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\MembersTeam|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class MembersTeamsTable extends Table
{
    use \Burzum\Cake\Service\ServiceAwareTrait;

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('members_teams');
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
            'Members',
            [
            'foreignKey' => 'member_id',
            'joinType' => 'INNER'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['member_id'], 'Members'));

        return $rules;
    }

    public function beforeSave(Event $event, \App\Model\Entity\MembersTeam $entity, ArrayObject $options)
    {
        if ($entity->isDirty('member_id') && !$entity->isNew()) {
            $this->loadService('Transfert');
            
            $this->Transfert->saveTeamMember($entity);
        }
    }
}
