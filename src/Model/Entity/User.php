<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\IdentityInterface as AuthenticationIdentity;
use Authorization\IdentityInterface as AuthorizationIdentity;
use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Webauthn\PublicKeyCredentialUserEntity;
use Authorization\Policy\ResultInterface;

/**
 * User Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $email
 * @property bool|null $active
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

    public function hasTeam($teamId)
    {
        return !empty(Hash::filter($this->teams, function (Team $value) use ($teamId) {
            return $value->id == $teamId;
        }));
    }

    public function isInChampionship($championshipId)
    {
        return !empty(Hash::filter($this->teams, function (Team $value) use ($championshipId) {
            return $value->championship_id == $championshipId;
        }));
    }

    public function isChampionshipAdmin($championshipId)
    {
        return !empty(Hash::filter($this->teams, function (Team $value) use ($championshipId) {
            return $value->championship_id == $championshipId && $value->admin;
        }));
    }

    /**
     * Authorization\IdentityInterface method
     */
    public function can(string $action, $resource): bool
    {
        return $this->authorization->can($this, $action, $resource);
    }

    public function canResult(string $action, $resource): ResultInterface
    {
        return $this->authorization->canResult($this, $action, $resource);
    }


    /**
     * Authorization\IdentityInterface method
     */
    public function applyScope($action, $resource)
    {
        return $this->authorization->applyScope($this, $action, $resource);
    }

    /**
     * Authorization\IdentityInterface method
     */
    public function getOriginalData()
    {
        return $this;
    }

    /**
     * Setter to be used by the middleware.
     */
    public function setAuthorization($service)
    {
        $this->authorization = $service;

        return $this;
    }

    /**
     * Authentication\IdentityInterface method
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    public function toCredentialUserEntity(): PublicKeyCredentialUserEntity
    {
        return new PublicKeyCredentialUserEntity($this->email, $this->uuid, $this->name . ' ' . $this->surname);
    }
}
