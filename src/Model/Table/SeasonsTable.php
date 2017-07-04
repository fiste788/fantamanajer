<?php
namespace App\Model\Table;

use App\Model\Entity\Season;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Seasons Model
 *
 * @property \Cake\ORM\Association\HasMany $Championships
 * @property \Cake\ORM\Association\HasMany $Matchdays
 * @property \Cake\ORM\Association\HasMany $Members
 * @property \Cake\ORM\Association\HasMany $View0LineupsDetails
 * @property \Cake\ORM\Association\HasMany $View0Members
 * @property \Cake\ORM\Association\HasMany $View1MembersStats
 * @property \Cake\ORM\Association\HasMany $View2ClubsStats
 */
class SeasonsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('seasons');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->hasMany('Championships', [
            'foreignKey' => 'season_id'
        ]);
        $this->hasMany('Matchdays', [
            'foreignKey' => 'season_id'
        ]);
        $this->hasMany('Members', [
            'foreignKey' => 'season_id'
        ]);
        $this->hasMany('View0LineupsDetails', [
            'foreignKey' => 'season_id'
        ]);
        $this->hasMany('View0Members', [
            'foreignKey' => 'season_id'
        ]);
        $this->hasMany('View1MembersStats', [
            'foreignKey' => 'season_id'
        ]);
        $this->hasMany('View2ClubsStats', [
            'foreignKey' => 'season_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('year', 'create')
            ->notEmpty('year');

        $validator
            ->requirePresence('key_gazzetta', 'create')
            ->notEmpty('key_gazzetta');

        return $validator;
    }
}
