<?php
namespace App\Model\Table;

use App\Model\Entity\Season;
use App\Model\Entity\User;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Teams Model
 *
 * @property BelongsTo $Users
 * @property BelongsTo $Championships
 * @property HasMany $Articles
 * @property HasMany $Events
 * @property HasMany $Lineups
 * @property HasMany $Scores
 * @property HasMany $Selections
 * @property HasMany $Transferts
 * @property HasMany $View0LineupsDetails
 * @property HasMany $View1MatchdayWin
 * @property BelongsToMany $Members
 */
class TeamsTable extends Table
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

        $this->setTable('teams');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'photo' => [
                'path' => 'webroot{DS}files{DS}{model}{DS}{primaryKey}{DS}{field}{DS}',
                'fields' => [
                    // if these fields or their defaults exist
                    // the values will be set.
                    'dir' => 'photo_dir', // defaults to `dir`
                    'size' => 'photo_size', // defaults to `size`
                    'type' => 'photo_type', // defaults to `type`
                ],
                'nameCallback' => function ($data, $settings) {
                    return strtolower($data['name']);
                },
                /*'transformer' =>  function ($table, $entity, $data, $field, $settings) {
                    $extension = pathinfo($data['name'], PATHINFO_EXTENSION);

                    // Store the thumbnail in a temporary file
                    $tmp = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                    // Use the Imagine library to DO THE THING
                    /*$size = new \Imagine\Image\Box(40, 40);
                    $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                    $imagine = new \Imagine\Gd\Imagine();

                    // Save that modified file to our temp file
                    $imagine->open($data['tmp_name'])
                        ->thumbnail($size, $mode)
                        ->save($tmp);

                    // Now return the original *and* the thumbnail
                    return [
                        $data['tmp_name'] => $data['name'],
                        //$tmp => 'thumbnail-' . $data['name'],
                    ];
                },*/
                'deleteCallback' => function ($path, $entity, $field, $settings) {
                    // When deleting the entity, both the original and the thumbnail will be removed
                    // when keepFilesOnDelete is set to false
                    return [
                        $path . $entity->{$field},
                        $path . 'thumbnail-' . $entity->{$field}
                    ];
                },
                'keepFilesOnDelete' => false
            ],
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Championships', [
            'foreignKey' => 'championship_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Articles', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('Events', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('Lineups', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('Scores', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('Selections', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('Transferts', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('View0LineupsDetails', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('View1MatchdayWin', [
            'foreignKey' => 'team_id'
        ]);
        $this->belongsToMany('Members', [
            'foreignKey' => 'team_id',
            'targetForeignKey' => 'member_id',
            'joinTable' => 'members_teams',
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['championship_id'], 'Championships'));
        return $rules;
    }
	
	public function findCurrent(Query $query, array $options) 
	{
		$matchdays = TableRegistry::get('Matchdays');
		$current = $matchdays->getCurrent();
		$query->matching('Championships', function($q) use ($current) {
			return $q->where(['Championships.season_id' => $current->season_id]);
		});
		return $query;
	}
	
	 /**
	 * 
	 * @param User $user
	 * @param Season $season
	 */
	public function findByUserAndSeason($user,$season) 
	{
		return $this->find()->where(['user_id' => $user->id])
				->matching('Championships', function($q) use ($season) {
					return $q->where(['Championships.season_id' => $season->season_id]);
				});
	}
}
