<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\IdentityInterface as AuthenticationIdentity;
use Authorization\IdentityInterface as AuthorizationIdentity;
use Authorization\Policy\ResultInterface;
use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Webauthn\PublicKeyCredentialUserEntity;

/**
 * User Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $surname
 * @property string $email
 * @property bool $active
 * @property bool $active_email
 * @property string $username
 * @property string $password
 * @property string|null $login_key
 * @property bool $admin
 * @property string|null $uuid
 *
 * @property \App\Model\Entity\PublicKeyCredentialSource[] $public_key_credential_sources
 * @property \App\Model\Entity\Team[] $teams
 * @property \App\Model\Entity\PushSubscription[] $push_subscriptions
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
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'surname' => true,
        'email' => true,
        'active' => true,
        'active_email' => true,
        'username' => true,
        'password' => true,
        'login_key' => true,
        'admin' => true,
        'uuid' => true,
        'public_key_credential_sources' => true,
        'teams' => true,
        'push_subscriptions' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
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
        return !empty(Hash::filter($this->teams, function (Team $value) use ($teamId) {
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
        return !empty(Hash::filter($this->teams, function (Team $value) use ($championshipId) {
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
        return !empty(Hash::filter($this->teams, function (Team $value) use ($championshipId) {
            return $value->championship_id == $championshipId && $value->admin;
        }));
    }

    /**
     * @inheritDoc
     */
    public function can(string $action, $resource): bool
    {
        return $this->authorization->can($this, $action, $resource);
    }

    /**
     * @inheritDoc
     */
    public function canResult(string $action, $resource): ResultInterface
    {
        return $this->authorization->canResult($this, $action, $resource);
    }

    /**
     * @inheritDoc
     */
    public function applyScope(string $action, $resource)
    {
        return $this->authorization->applyScope($this, $action, $resource);
    }

    /**
     * @inheritDoc
     */
    public function getOriginalData()
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setAuthorization($service)
    {
        $this->authorization = $service;

        return $this;
    }

    /**
     * @inheritDoc
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
        return new PublicKeyCredentialUserEntity($this->email, $this->uuid ?? '', $this->name . ' ' . $this->surname);
    }
}
