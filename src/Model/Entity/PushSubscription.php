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
 * @property string|null $public_key
 * @property string|null $auth_token
 * @property string|null $content_encoding
 * @property \Cake\I18n\DateTime|null $expires_at
 * @property \Cake\I18n\DateTime $created_at
 * @property \Cake\I18n\DateTime $modified_at
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
     * @var array<string, bool>
     */
    protected array $_accessible = [
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
     * @var list<string>
     */
    protected array $_hidden = [
        'auth_token',
    ];

    /**
     * @return \WebPush\Subscription|null
     * @throws \ErrorException
     */
    public function toSubscription(): ?Subscription
    {
        /** @var string $encodings */
        $encodings = $this->content_encoding ?? 'aesgcm';
        $subscriptionString = json_encode([
            'endpoint' => $this->endpoint,
            'supportedContentEncodings' => [$encodings],
            'keys' => [
                'p256dh' => $this->public_key,
                'auth' => $this->auth_token,
            ],
        ]);

        return $subscriptionString != false ? Subscription::createFromString($subscriptionString) : null;
    }
}
