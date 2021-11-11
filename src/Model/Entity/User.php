<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\IdentityInterface as AuthenticationIdentity;
use Authorization\AuthorizationServiceInterface;
use Authorization\IdentityInterface as AuthorizationIdentity;
use Authorization\Policy\ResultInterface;
use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Webauthn\PublicKeyCredentialUserEntity;

/**
 * User Entity
 *
 * @property int $id
 * @property string|null $uuid
 * @property string|null $email
 * @property string|null $name
 * @property string|null $surname
 * @property string $username
 * @property string $password
 * @property string|null $login_key
 * @property bool $active
 * @property bool $admin
 * @property bool $active_email
 *
 * @property \App\Model\Entity\PublicKeyCredentialSource[] $public_key_credential_sources
 * @property \App\Model\Entity\PushSubscription[] $push_subscriptions
 * @property \App\Model\Entity\Team[] $teams
 * @property \Authorization\AuthorizationServiceInterface $authorization
 * @property int $old_id
 */
class User extends Entity implements AuthorizationIdentity, AuthenticationIdentity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var bool[]
     */
    protected $_accessible = [
        'uuid' => false,
        'email' => true,
        'name' => true,
        'surname' => true,
        'username' => true,
        'password' => true,
        'login_key' => true,
        'active' => true,
        'admin' => false,
        'active_email' => true,
        'push_subscriptions' => false,
        'teams' => false,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var string[]
     */
    protected $_hidden = [
        'password',
        'login_key',
        'uuid',
    ];

    /**
     * Has team
     *
     * @param int $teamId Id
     * @return bool
     */
    public function hasTeam(int $teamId): bool
    {
        return !empty(Hash::filter($this->teams, function (Team $value) use ($teamId): bool {
            return $value->id == $teamId;
        }));
    }

    /**
     * In in championship
     *
     * @param int $championshipId Id
     * @return bool
     */
    public function isInChampionship(int $championshipId): bool
    {
        return !empty(Hash::filter($this->teams, function (Team $value) use ($championshipId): bool {
            return $value->championship_id == $championshipId;
        }));
    }

    /**
     * Is championship admin
     *
     * @param int $championshipId Id
     * @return bool
     */
    public function isChampionshipAdmin(int $championshipId): bool
    {
        return !empty(Hash::filter($this->teams, function (Team $value) use ($championshipId): bool {
            return $value->championship_id == $championshipId && $value->admin;
        }));
    }

    /**
     * Undocumented function
     *
     * @param string $action Action
     * @param mixed $resource Resource
     * @return bool
     */
    public function can(string $action, $resource): bool
    {
        return $this->authorization->can($this, $action, $resource);
    }

    /**
     * Undocumented function
     *
     * @param string $action Action
     * @param mixed $resource Resource
     * @return \Authorization\Policy\ResultInterface
     */
    public function canResult(string $action, $resource): ResultInterface
    {
        return $this->authorization->canResult($this, $action, $resource);
    }

    /**
     * Undocumented function
     *
     * @param string $action Action
     * @param mixed $resource Resource
     * @return mixed
     */
    public function applyScope(string $action, $resource)
    {
        return $this->authorization->applyScope($this, $action, $resource);
    }

    /**
     * Undocumented function
     *
     * @return self
     */
    public function getOriginalData(): User
    {
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param \Authorization\AuthorizationServiceInterface $service Authorization service
     * @return self
     */
    public function setAuthorization(AuthorizationServiceInterface $service): User
    {
        $this->authorization = $service;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return int
     */
    public function getIdentifier(): int
    {
        return $this->id;
    }

    /**
     * Public user entity
     *
     * @return \Webauthn\PublicKeyCredentialUserEntity
     */
    public function toCredentialUserEntity(): PublicKeyCredentialUserEntity
    {
        return new PublicKeyCredentialUserEntity(
            (string)($this->email ?? ''),
            (string)($this->uuid ?? ''),
            (string)($this->name ?? '') . ' ' . (string)($this->surname ?? '')
        );
    }
}
