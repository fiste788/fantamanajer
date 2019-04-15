<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Seasons Model
 *
 * @property \App\Model\Table\ChampionshipsTable|\Cake\ORM\Association\HasMany $Championships
 * @property \App\Model\Table\MatchdaysTable|\Cake\ORM\Association\HasMany $Matchdays
 * @property \App\Model\Table\MembersTable|\Cake\ORM\Association\HasMany $Members
 * @property \Cake\ORM\Association\HasMany $View0LineupsDetails
 * @property \Cake\ORM\Association\HasMany $View0Members
 * @property \Cake\ORM\Association\HasMany $View1MembersStats
 * @property \Cake\ORM\Association\HasMany $View2ClubsStats
 * @method \App\Model\Entity\Season get($primaryKey, $options = [])
 * @method \App\Model\Entity\Season newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Season[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Season|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Season patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Season[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Season findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Season|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class SeasonsTable extends Table
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

        $this->setTable('seasons');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany(
            'Championships',
            [
                'foreignKey' => 'season_id'
            ]
        );
        $this->hasMany(
            'Matchdays',
            [
                'foreignKey' => 'season_id'
            ]
        );
        $this->hasMany(
            'Members',
            [
                'foreignKey' => 'season_id'
            ]
        );
        $this->hasMany(
            'View0LineupsDetails',
            [
                'foreignKey' => 'season_id'
            ]
        );
        $this->hasMany(
            'View0Members',
            [
                'foreignKey' => 'season_id'
            ]
        );
        $this->hasMany(
            'View1MembersStats',
            [
                'foreignKey' => 'season_id'
            ]
        );
        $this->hasMany(
            'View2ClubsStats',
            [
                'foreignKey' => 'season_id'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
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

        return $validator;
    }
}
