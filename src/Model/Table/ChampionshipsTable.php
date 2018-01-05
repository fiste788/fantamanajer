<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Championships Model
 *
 * @property \App\Model\Table\LeaguesTable|\Cake\ORM\Association\BelongsTo $Leagues
 * @property \App\Model\Table\SeasonsTable|\Cake\ORM\Association\BelongsTo $Seasons
 * @property \App\Model\Table\TeamsTable|\Cake\ORM\Association\HasMany $Teams
 *
 * @method \App\Model\Entity\Championship get($primaryKey, $options = [])
 * @method \App\Model\Entity\Championship newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Championship[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Championship|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Championship patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Championship[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Championship findOrCreate($search, callable $callback = null, $options = [])
 */
class ChampionshipsTable extends Table
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

        $this->setTable('championships');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Leagues',
            [
            'foreignKey' => 'league_id',
            'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Seasons',
            [
            'foreignKey' => 'season_id',
            'joinType' => 'INNER'
            ]
        );
        $this->hasMany(
            'Teams',
            [
            'foreignKey' => 'championship_id'
            ]
        );
        $this->hasMany(
            'View0MaxPoints',
            [
            'foreignKey' => 'championship_id'
            ]
        );
        $this->hasMany(
            'View2TeamsStats',
            [
            'foreignKey' => 'championship_id'
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
            ->boolean('captain')
            ->requirePresence('captain', 'create')
            ->notEmpty('captain');

        $validator
            ->integer('number_transferts')
            ->requirePresence('number_transferts', 'create')
            ->notEmpty('number_transferts');

        $validator
            ->integer('number_selections')
            ->requirePresence('number_selections', 'create')
            ->notEmpty('number_selections');

        $validator
            ->integer('minute_lineup')
            ->requirePresence('minute_lineup', 'create')
            ->notEmpty('minute_lineup');

        $validator
            ->integer('points_missed_lineup')
            ->requirePresence('points_missed_lineup', 'create')
            ->notEmpty('points_missed_lineup');

        $validator
            ->boolean('captain_missed_lineup')
            ->requirePresence('captain_missed_lineup', 'create')
            ->notEmpty('captain_missed_lineup');

        $validator
            ->boolean('jolly')
            ->allowEmpty('jolly');

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
        $rules->add($rules->existsIn(['league_id'], 'Leagues'));
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));

        return $rules;
    }
}
