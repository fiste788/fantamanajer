<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use WebPush\Subscription;

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
 * @property \Cake\I18n\FrozenTime|null $modified_at
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
     * @var bool[]
     */
    protected $_accessible = [
        'endpoint' => true,
        'public_key' => true,
        'auth_token' => true,
        'content_encoding' => true,
        'expires_at' => true,
        'created_at' => false,
        'modified_at' => false,
        'user_id' => true,
        'user' => true,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var string[]
     */
    protected $_hidden = [
        'auth_token',
    ];

    /**
     * @return \WebPush\Subscription|null
     * @throws \ErrorException
     */
    public function getSubscription(): ?Subscription
    {
        $subscriptionString = json_encode([
            'endpoint' => $this->endpoint,
            'contentEncoding' => $this->content_encoding ?? 'aesgcm',
            'keys' => [
                'p256dh' => $this->public_key,
                'auth' => $this->auth_token,
            ],
        ]);

        return $subscriptionString != false ? Subscription::createFromString($subscriptionString) : null;
    }
}
