<?php

namespace App\Model\Table;

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;


/**
 * Articles Model
 *
 * @property BelongsTo $Teams
 * @property BelongsTo $Matchdays
 */
class ArticlesTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('articles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'modified_at' => 'always'
                ]
            ]
        ]);

        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Matchdays', [
            'foreignKey' => 'matchday_id',
            'joinType' => 'INNER'
        ]);
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new'
                ]
            ]
        ]);
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {
        $data['matchday_id'] = TableRegistry::get('Matchdays')->findCurrent()->id;
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->requirePresence('title', 'create')
                ->notEmpty('title');

        $validator
                ->allowEmpty('subtitle');

        $validator
                ->requirePresence('body', 'create')
                ->notEmpty('body');

        /* $validator
          ->dateTime('created_at')
          ->requirePresence('created_at', 'create')
          ->notEmpty('created_at'); */

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        return $rules;
    }

    public function findByChampionshipId($championshipId, $options = []) {
        return $this->find('all', $options)->matching('Teams', function($q) use ($championshipId) {
                    return $q->where(['Teams.championship_id' => $championshipId]);
                })->all();
    }

    public function findByChampionship(Query $query, $options = []) {
        $championshipId = $options['championshipId'];
        return $query->matching('Teams', function($q) use ($championshipId) {
                    return $q->where(['Teams.championship_id' => $championshipId]);
                });
    }

}
