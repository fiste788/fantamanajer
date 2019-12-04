<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Team;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Psr\Http\Message\UploadedFileInterface;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use SplFileInfo;

/**
 * Teams Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ChampionshipsTable&\Cake\ORM\Association\BelongsTo $Championships
 * @property \App\Model\Table\ArticlesTable&\Cake\ORM\Association\HasMany $Articles
 * @property \App\Model\Table\LineupsTable&\Cake\ORM\Association\HasMany $Lineups
 * @property \App\Model\Table\NotificationSubscriptionsTable&\Cake\ORM\Association\HasMany $EmailNotificationSubscriptions
 * @property \App\Model\Table\NotificationSubscriptionsTable&\Cake\ORM\Association\HasMany $PushNotificationSubscriptions
 * @property \App\Model\Table\ScoresTable&\Cake\ORM\Association\HasMany $Scores
 * @property \App\Model\Table\SelectionsTable&\Cake\ORM\Association\HasMany $Selections
 * @property \App\Model\Table\TransfertsTable&\Cake\ORM\Association\HasMany $Transferts
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsToMany $Members
 * @property \App\Service\TeamService $Team
 *
 * @method \App\Model\Entity\Team get($primaryKey, $options = [])
 * @method \App\Model\Entity\Team newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Team[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Team|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Team saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Team patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Team[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Team findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Josegonzalez\Upload\Model\Behavior\UploadBehavior
 */
class TeamsTable extends Table
{
    use ServiceAwareTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('teams');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Championships', [
            'foreignKey' => 'championship_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Articles', [
            'foreignKey' => 'team_id',
        ]);
        $this->hasMany('Events', [
            'foreignKey' => 'team_id',
        ]);
        $this->hasMany('Lineups', [
            'foreignKey' => 'team_id',
        ]);
        $this->hasMany('EmailNotificationSubscriptions', [
            'className' => 'NotificationSubscriptions',
            'foreignKey' => 'team_id',
            'conditions' => ['type' => 'email'],
            'saveStrategy' => 'replace',
        ]);
        $this->hasMany('PushNotificationSubscriptions', [
            'className' => 'NotificationSubscriptions',
            'foreignKey' => 'team_id',
            'conditions' => ['type' => 'push'],
            'saveStrategy' => 'replace',
        ]);
        $this->hasMany('Scores', [
            'foreignKey' => 'team_id',
        ]);
        $this->hasMany('Selections', [
            'foreignKey' => 'team_id',
        ]);
        $this->hasMany('Transferts', [
            'foreignKey' => 'team_id',
        ]);
        $this->belongsToMany('Members', [
            'foreignKey' => 'team_id',
            'targetForeignKey' => 'member_id',
            'joinTable' => 'members_teams',
            'sort' => ['role_id'],
        ]);
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'photo' => [
                'path' => WWW_ROOT . 'files{DS}{table}{DS}{primaryKey}{DS}{field}{DS}',
                'fields' => [
                    'dir' => 'photo_dir', // defaults to `dir`
                    'size' => 'photo_size', // defaults to `size`
                    'type' => 'photo_type', // defaults to `type`
                ],
                'nameCallback' => function (array $data, array $settings) {
                    return strtolower($data['name']);
                },
                'transformer' => function (
                    RepositoryInterface $table,
                    EntityInterface $entity,
                    UploadedFileInterface $file,
                    string $field,
                    array $settings
                ) {
                    $tmpFileName = new SplFileInfo($file->getClientFilename() ?? $entity->get('id') . '.jpg');
                    $tmpFile = tempnam(TMP, $tmpFileName->getFilename());
                    if ($tmpFile != false) {
                        $file->moveTo($tmpFile);
                        $image = Image::load($tmpFile);
                        $array = [$tmpFile => $tmpFileName->getFilename()];
                        foreach (Team::$size as $value) {
                            if ($value < $image->getWidth()) {
                                $manipulations = (new Manipulations())->width($value)->optimize();
                                $tmp = tempnam(TMP, (string)$value) . '.' . $tmpFileName->getExtension();
                                $image->manipulate($manipulations)->save($tmp);
                                $array[$tmp] = $value . 'w' . DS . $tmpFileName->getFilename();
                            }
                        }

                        return $array;
                    }
                },
                'deleteCallback' => function (
                    string $path,
                    EntityInterface $entity,
                    string $field,
                    array $settings
                ) {
                    $array = [$path . $entity->{$field}];
                    foreach (Team::$size as $value) {
                        $array[] = $path . $value . DS . $entity->{$field};
                    }

                    return $array;
                },
                'keepFilesOnDelete' => false,
            ],
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->boolean('admin')
            ->notEmptyString('admin');

        $validator
            ->scalar('photo')
            ->maxLength('photo', 255)
            ->allowEmptyString('photo');

        $validator
            ->scalar('photo_dir')
            ->maxLength('photo_dir', 255)
            ->allowEmptyString('photo_dir');

        $validator
            ->integer('photo_size')
            ->allowEmptyString('photo_size');

        $validator
            ->scalar('photo_type')
            ->maxLength('photo_type', 255)
            ->allowEmptyString('photo_type');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['championship_id'], 'Championships'));
        $rules->add($rules->isUnique(['name', 'championship_id'], __('Team name already exist in this championship')));
        $rules->add(
            $rules->isUnique(['user_id', 'championship_id'], __('Team name already exist in this championship'))
        );

        return $rules;
    }

    /**
     * Return find by championship query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query Query
     */
    public function findByChampionshipId(Query $q, array $options): Query
    {
        return $q->contain(['Users'])
            ->where(['championship_id' => $options['championship_id']]);
    }

    /**
     * Save without user
     *
     * @param \App\Model\Entity\Team $team Team
     * @return void
     */
    public function saveWithoutUser(Team $team): void
    {
        $this->loadService("Team");

        if (!$team->user->id) {
            $team->user = $this->Users->findOrCreate(['email' => $team->user->email]);
        }
        if (!$team->user->id) {
            $team->user->active = false;
        }
        $this->Team->createTeam($team);
    }
}
