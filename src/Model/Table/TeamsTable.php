<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Team;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Psr\Http\Message\UploadedFileInterface;
use Spatie\Image\Enums\ImageDriver;
use Spatie\Image\Image;
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
 * @method \App\Model\Entity\Team get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Team newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Team[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Team|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Team saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Team patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Team[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Team findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Team newEmptyEntity()
 * @method \App\Model\Entity\Team[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Team[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Team[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\Team[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Josegonzalez\Upload\Model\Behavior\UploadBehavior
 */
class TeamsTable extends Table
{
    use ServiceAwareTrait;

    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     * @throws \RuntimeException
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
                'path' => 'webroot{DS}img{DS}{table}{DS}{primaryKey}{DS}{field}{DS}',
                'fields' => [
                    'dir' => 'photo_dir',
                    // defaults to `dir`
                    'size' => 'photo_size',
                    // defaults to `size`
                    'type' => 'photo_type',
                    // defaults to `type`
                ],
                'nameCallback' =>
                function (
                    RepositoryInterface $_table,
                    EntityInterface $_entity,
                    UploadedFileInterface $file,
                    string $_field,
                    array $_settings
                ) {
                    return strtolower($file->getClientFilename() ?? (string)$_entity->get('id'));
                },
                'transformer' =>
                function (
                    RepositoryInterface $_table,
                    EntityInterface $entity,
                    UploadedFileInterface $file,
                    string $_field,
                    array $_settings
                ) {
                    $tmpFileName = new SplFileInfo(
                        strtolower($file->getClientFilename() ?? (string)$entity->get('id') . '.jpg')
                    );
                    $tmpFile = tempnam(TMP, $tmpFileName->getFilename());
                    if ($tmpFile != false) {
                        $file->moveTo($tmpFile);
                        $image = Image::useImageDriver(ImageDriver::Gd)->load($tmpFile);
                        $array = [$tmpFile => $tmpFileName->getFilename()];
                        foreach (Team::$size as $value) {
                            if ($value < $image->getWidth()) {
                                $tmp = tempnam(TMP, (string)$value) . '.' . $tmpFileName->getExtension();
                                $image->width($value)->optimize()->save($tmp);
                                $array[$tmp] = $value . 'w' . DS . strtolower($tmpFileName->getFilename());
                            }
                        }

                        return $array;
                    }
                },
                'deleteCallback' => function (string $path, EntityInterface $entity, string $field, array $_settings) {
                    $array = [$path . (string)$entity->{$field}];
                    foreach (Team::$size as $value) {
                        $array[] = $path . $value . DS . (string)$entity->{$field};
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
     * @throws \InvalidArgumentException
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
     * @throws \Cake\Core\Exception\CakeException If a rule with the same name already exists
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
     * @param \Cake\ORM\Query\SelectQuery $q Query
     * @param mixed ...$args
     * @return \Cake\ORM\Query\SelectQuery Query
     */
    public function findByChampionshipId(SelectQuery $q, mixed ...$args): SelectQuery
    {
        return $q->contain(['Users'])
            ->where(['championship_id' => $args['championship_id']]);
    }

    /**
     * Save without user
     *
     * @param \App\Model\Entity\Team $team Team
     * @return void
     * @throws \Cake\ORM\Exception\PersistenceFailedException
     * @throws \GetStream\Stream\StreamFeedException
     * @throws \Cake\Core\Exception\CakeException
     */
    public function saveWithoutUser(Team $team): void
    {
        $this->loadService('Team');

        if (!$team->user->id) {
            $user = $this->Users->findOrCreate(['email' => $team->user->email]);
            $team->user = $user;
        }
        if (!$team->user->id) {
            $team->user->active = false;
        }
        $this->Team->createTeam($team);
    }

    /**
     * Return teams ordered
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args
     * @return \Cake\ORM\Query\SelectQuery Query
     */
    public function findTeamsOrdered(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->contain([
            'Championships' => [
                'Leagues',
                'Seasons',
            ],
        ])->innerJoinWith('Championships.Seasons')->orderBy(['Seasons.year' => 'DESC']);
    }
}
