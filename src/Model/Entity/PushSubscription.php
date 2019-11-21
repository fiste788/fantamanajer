<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Minishlink\WebPush\Subscription;

/**
 * PushSubscription Entity
 *
 * @property string $id
 * @property string $endpoint
 * @property string $public_key
 * @property string $auth_token
 * @property string|null $content_encoding
 * @property \Cake\I18n\FrozenTime $expires_at
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $user_id
 *
 * @property \App\Model\Entity\User $user
 */
class PushSubscription extends Entity
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
        'endpoint' => true,
        'public_key' => true,
        'auth_token' => true,
        'content_encoding' => true,
        'expires_at' => true,
        'created_at' => true,
        'modified_at' => true,
        'user_id' => true,
        'user' => true,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'auth_token',
    ];

    /**
     *
     * @return \Minishlink\WebPush\Subscription
     */
    public function getSubscription(): Subscription
    {
        return Subscription::create([
            'endpoint' => $this->endpoint,
            'publicKey' => $this->public_key,
            'authToken' => $this->auth_token,
            'contentEncoding' => 'aesgcm',
        ]);
    }
}
