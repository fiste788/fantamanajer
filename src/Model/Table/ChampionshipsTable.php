<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Championships Model
 *
 * @property \App\Model\Table\LeaguesTable&\Cake\ORM\Association\BelongsTo $Leagues
 * @property \App\Model\Table\SeasonsTable&\Cake\ORM\Association\BelongsTo $Seasons
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\HasMany $Teams
 *
 * @method \App\Model\Entity\Championship get($primaryKey, $options = [])
 * @method \App\Model\Entity\Championship newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Championship[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Championship|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Championship saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Championship patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Championship[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Championship findOrCreate($search, callable $callback = null, $options = [])
 */
class ChampionshipsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('championships');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Leagues', [
            'foreignKey' => 'league_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Seasons', [
            'foreignKey' => 'season_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Teams', [
            'foreignKey' => 'championship_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->boolean('captain')
            ->notEmptyString('captain');

        $validator
            ->notEmptyString('number_transferts');

        $validator
            ->notEmptyString('number_selections');

        $validator
            ->notEmptyString('minute_lineup');

        $validator
            ->notEmptyString('points_missed_lineup');

        $validator
            ->boolean('captain_missed_lineup')
            ->notEmptyString('captain_missed_lineup');

        $validator
            ->boolean('started')
            ->notEmptyString('started');

        $validator
            ->boolean('jolly')
            ->allowEmptyString('jolly');

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
        $rules->add($rules->existsIn(['league_id'], 'Leagues'));
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));

        return $rules;
    }
}
