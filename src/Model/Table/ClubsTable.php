<?php
namespace App\Model\Table;

use App\Model\Entity\Season;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Clubs Model
 *
 * @property HasMany $Members
 * @property HasMany $View0LineupsDetails
 * @property HasMany $View0Members
 * @property HasMany $View1MembersStats
 */
class ClubsTable extends Table
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

        $this->setTable('clubs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Members', [
            'foreignKey' => 'club_id'
        ]);
        $this->hasMany('View0LineupsDetails', [
            'foreignKey' => 'club_id'
        ]);
        $this->hasMany('View0Members', [
            'foreignKey' => 'club_id'
        ]);
        $this->hasMany('View1MembersStats', [
            'foreignKey' => 'club_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
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
            ->requirePresence('partitive', 'create')
            ->notEmpty('partitive');

        $validator
            ->requirePresence('determinant', 'create')
            ->notEmpty('determinant');

        return $validator;
    }
	
	/**
     * 
     * @param Season $season
     * @return type
     */
    public function findBySeason($season) {
        $members = TableRegistry::get('Members');
        $ids = $members->find()->select(['club_id'])->distinct(['club_id'])->where(['season_id'=>$season->id]);
        return $this->find('all')->where([
            'id IN' => $ids
        ])->order(['name'])->all();
    }
}
