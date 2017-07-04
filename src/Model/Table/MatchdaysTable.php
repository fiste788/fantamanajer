<?php
namespace App\Model\Table;

use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use DateTime;

/**
 * Matchdays Model
 *
 * @property BelongsTo $Seasons
 * @property HasMany $Articles
 * @property HasMany $Lineups
 * @property HasMany $Ratings
 * @property HasMany $Scores
 * @property HasMany $Transferts
 */
class MatchdaysTable extends Table
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

        $this->table('matchdays');
        $this->displayField('number');
        $this->primaryKey('id');

        $this->belongsTo('Seasons', [
            'foreignKey' => 'season_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Articles', [
            'foreignKey' => 'matchday_id'
        ]);
        $this->hasMany('Lineups', [
            'foreignKey' => 'matchday_id'
        ]);
        $this->hasMany('Ratings', [
            'foreignKey' => 'matchday_id'
        ]);
        $this->hasMany('Scores', [
            'foreignKey' => 'matchday_id'
        ]);
        $this->hasMany('Transferts', [
            'foreignKey' => 'matchday_id'
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
            ->integer('number')
            ->requirePresence('number', 'create')
            ->notEmpty('number');

        $validator
            ->dateTime('date')
            ->requirePresence('date', 'create')
            ->notEmpty('date');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));
        return $rules;
    }
	
	public function getCurrent() 
	{
        return $this
				->find('all')
				->where([
					'date > ' => new DateTime('now') 
				])
				->first();
	}
	
	public function getTargetCountdown($minutes = 0) 
	{
		$query = $this->find();
		$expr = $query->newExpr()->add('MIN(date) - INTERVAL ' . $minutes . ' MINUTE');
		$query->select(['date' => $expr])->where(['NOW() < date']);
		return $query->first()->date;
	}
}
