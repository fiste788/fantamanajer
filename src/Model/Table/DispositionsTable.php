<?php
namespace App\Model\Table;

use App\Model\Entity\Disposition;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dispositions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Lineups
 * @property \Cake\ORM\Association\BelongsTo $Members
 * @property \Cake\ORM\Association\HasMany $View0LineupsDetails
 */
class DispositionsTable extends Table
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
            'finder' => function ($q) {
                return $q->innerJoinWith('Lineups')
                    ->where(['Ratings.matchday_id' => 'Lineups.matchday_id'])
                    ->andWhere(['Ratings.member_id' => 'member_id']);
            }
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
     * @param  \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['lineup_id'], 'Lineups'));
        $rules->add($rules->existsIn(['member_id'], 'Members'));

        return $rules;
    }
}
