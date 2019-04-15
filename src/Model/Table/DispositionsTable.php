<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dispositions Model
 *
 * @property \App\Model\Table\LineupsTable|\Cake\ORM\Association\BelongsTo $Lineups
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\BelongsTo $Members
 *
 * @method \App\Model\Entity\Disposition get($primaryKey, $options = [])
 * @method \App\Model\Entity\Disposition newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Disposition[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Disposition|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Disposition patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Disposition[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Disposition findOrCreate($search, callable $callback = null, $options = [])
 * @property \App\Model\Table\RatingsTable|\Cake\ORM\Association\HasOne $Ratings
 * @method \App\Model\Entity\Disposition|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class DispositionsTable extends Table
{

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('dispositions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Lineups',
            [
                'foreignKey' => 'lineup_id',
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
        $this->hasMany(
            'View0LineupsDetails',
            [
                'foreignKey' => 'disposition_id'
            ]
        );
        $this->hasOne(
            'Ratings',
            [
                'finder' => 'byMatchdayLineup'
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
            ->integer('position')
            ->requirePresence('position', 'create')
            ->notEmpty('position');

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
        $rules->add($rules->existsIn(['lineup_id'], 'Lineups'));
        $rules->add($rules->existsIn(['member_id'], 'Members'));

        return $rules;
    }

    public function findByMatchdayLineup(Query $q, array $options)
    {
        return $q->innerJoinWith('Lineups')
            ->where(['Ratings.matchday_id' => 'Lineups.matchday_id'])
            ->andWhere(['Ratings.member_id' => 'member_id']);
    }
}
