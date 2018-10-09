<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Team Entity.
 *
 * @property int $id
 * @property string $name
 * @property boolean $admin
 * @property string $photo
 * @property string $photo_dir
 * @property int $photo_size
 * @property string $photo_type
 * @property int $user_id
 * @property \App\Model\Entity\User $user
 * @property int $championship_id
 * @property \App\Model\Entity\Championship $championship
 * @property \App\Model\Entity\NotificationSubscription[] $email_notification_subscriptions
 * @property \App\Model\Entity\NotificationSubscription[] $push_notification_subscriptions
 * @property \App\Model\Entity\Article[] $articles
 * @property \App\Model\Entity\Event[] $events
 * @property \App\Model\Entity\Lineup[] $lineups
 * @property \App\Model\Entity\Score[] $scores
 * @property \App\Model\Entity\Selection[] $selections
 * @property \App\Model\Entity\Transfert[] $transferts
 * @property \App\Model\Entity\View0LineupsDetail[] $view0_lineups_details
 * @property \App\Model\Entity\View1MatchdayWin[] $view1_matchday_win
 * @property \App\Model\Entity\Member[] $members
 * @property int $old_id
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
        '*' => true,
        'id' => false,
        'user' => false
    ];

    protected $_hidden = [
        'photo_dir',
        'photo_size',
        'photo_type'
    ];

    /**
     *
     * @var array
     */
    public static $size = [1280, 600, 240];

    protected $_virtual = ['photo_url'];

    protected function _getPhotoUrl()
    {
        if ($this->photo) {
            $baseUrl = '/files/' . strtolower($this->getSource()) . '/' . $this->id . '/photo/';

            return $this->_getPhotosUrl(ROOT . DS . $this->photo_dir, $baseUrl, $this->photo);
        }
    }

    public function isNotificationSubscripted($type, $name)
    {
        if ($type == 'email') {
            return $this->isSubscripted($this->email_notification_subscriptions, $name);
        } else {
            return $this->isSubscripted($this->push_notification_subscriptions, $name);
        }
    }

    public function isEmailSubscripted($name)
    {
        return $this->isSubscripted($this->push_notification_subscriptions, $name);
    }

    public function isPushSubscripted($name)
    {
        return $this->isSubscripted($this->push_notification_subscriptions, $name);
    }

    /**
     *
     * @param NotificationSubscription[] $collection
     */
    private function isSubscripted(array $collection, $name)
    {
        foreach ($collection as $subscription) {
            if ($subscription->name == $name && $subscription->enabled) {
                return true;
            }
        }

        return false;
    }
}
