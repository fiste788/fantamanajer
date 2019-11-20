<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Model\Entity\Traits\HasPhotoTrait;

/**
 * Team Entity
 *
 * @property int $id
 * @property string $name
 * @property bool $admin
 * @property string|null $photo
 * @property string|null $photo_dir
 * @property int|null $photo_size
 * @property string|null $photo_type
 * @property int $user_id
 * @property int $championship_id
 * @property array|null $photo_url
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Championship $championship
 * @property \App\Model\Entity\Article[] $articles
 * @property \App\Model\Entity\Lineup[] $lineups
 * @property \App\Model\Entity\NotificationSubscription[] $email_notification_subscriptions
 * @property \App\Model\Entity\NotificationSubscription[] $push_notification_subscriptions
 * @property \App\Model\Entity\Score[] $scores
 * @property \App\Model\Entity\Selection[] $selections
 * @property \App\Model\Entity\Transfert[] $transferts
 * @property \App\Model\Entity\Member[] $members
 */
class Team extends Entity
{
    use HasPhotoTrait;

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
        'admin' => true,
        'photo' => true,
        'photo_dir' => false,
        'photo_size' => false,
        'photo_type' => false,
        'user_id' => true,
        'championship_id' => true,
        'user' => true,
        'championship' => false,
        'articles' => false,
        'events' => false,
        'lineups' => false,
        'notification_subscriptions' => false,
        'scores' => false,
        'selections' => false,
        'transferts' => false,
        'members' => false,
    ];

    protected $_hidden = [
        'photo',
        'photo_dir',
        'photo_size',
        'photo_type',
    ];

    /**
     *
     * @var array
     */
    public static $size = [1280, 600, 240];

    protected $_virtual = ['photo_url'];

    /**
     * Get photo
     *
     * @return array|null
     */
    protected function _getPhotoUrl(): ?array
    {
        if ($this->photo != null) {
            $baseUrl = '/img/' . strtolower($this->getSource()) . '/' . $this->id . '/photo/';

            return $this->_getPhotosUrl(ROOT . DS . ($this->photo_dir ?? ''), $baseUrl, $this->photo);
        } else {
            return [];
        }
    }

    /**
     * is Subscripted
     *
     * @param string $type Type
     * @param string $name Name
     * @return bool
     */
    public function isNotificationSubscripted(string $type, string $name): bool
    {
        if ($type == 'email') {
            return $this->isSubscripted($this->email_notification_subscriptions, $name);
        } else {
            return $this->isSubscripted($this->push_notification_subscriptions, $name);
        }
    }

    /**
     * Is Subscripted
     *
     * @param string $name Name
     * @return bool
     */
    public function isEmailSubscripted(string $name): bool
    {
        return $this->isSubscripted($this->push_notification_subscriptions, $name);
    }

    /**
     * is Subscripted
     *
     * @param string $name Name
     * @return bool
     */
    public function isPushSubscripted(string $name): bool
    {
        return $this->isSubscripted($this->push_notification_subscriptions, $name);
    }

    /**
     *
     * @param \App\Model\Entity\NotificationSubscription[] $collection Collection
     * @param string $name Name
     * @return bool
     */
    private function isSubscripted(array $collection, string $name): bool
    {
        foreach ($collection as $subscription) {
            if ($subscription->name == $name && $subscription->enabled) {
                return true;
            }
        }

        return false;
    }
}
