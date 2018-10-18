<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Leagues Model
 *
 * @property \App\Model\Table\ChampionshipsTable|\Cake\ORM\Association\HasMany $Championships
 *
 * @method \App\Model\Entity\League get($primaryKey, $options = [])
 * @method \App\Model\Entity\League newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\League[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\League|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\League patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\League[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\League findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\League|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class LeaguesTable extends Table
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

        $this->setTable('leagues');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany(
            'Championships',
            [
            'foreignKey' => 'league_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->minLength('name', 3);

        return $validator;
    }
}
