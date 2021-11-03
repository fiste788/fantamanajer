<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\MembersTeam;
use ArrayObject;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MembersTeams Model
 *
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\BelongsTo $Teams
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $Members
 * @property \App\Service\TransfertService $Transfert
 * @method \App\Model\Entity\MembersTeam get($primaryKey, $options = [])
 * @method \App\Model\Entity\MembersTeam newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\MembersTeam[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MembersTeam|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MembersTeam saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MembersTeam patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MembersTeam[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\MembersTeam findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\MembersTeam newEmptyEntity()
 * @method \App\Model\Entity\MembersTeam[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MembersTeam[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\MembersTeam[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\MembersTeam[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class MembersTeamsTable extends Table
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

        $this->setTable('members_teams');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Members', [
            'foreignKey' => 'member_id',
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
        $rules->add($rules->existsIn(['member_id'], 'Members'));

        return $rules;
    }

    /**
     * Before save event
     *
     * @param \Cake\Event\Event $event Event
     * @param \App\Model\Entity\MembersTeam $entity Entity
     * @param \ArrayObject $options Options
     * @return void
     * @throws \InvalidArgumentException
     */
    public function beforeSave(Event $event, MembersTeam $entity, ArrayObject $options): void
    {
        if ($entity->isDirty('member_id') && !$entity->isNew()) {
            $this->loadService('Transfert');

            $this->Transfert->saveTeamMember($entity);
        }
    }
}
