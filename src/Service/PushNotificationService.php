<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\PushSubscription;
use App\Utility\AngularPushMessage;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\ORM\Locator\LocatorAwareTrait;
use Laminas\Diactoros\RequestFactory;
use WebPush\ExtensionManager;
use WebPush\Message;
use WebPush\NotificationInterface;
use WebPush\Payload\AES128GCM;
use WebPush\Payload\AESGCM;
use WebPush\Payload\PayloadExtension;
use WebPush\StatusReportInterface;
use WebPush\SubscriptionInterface;
use WebPush\TopicExtension;
use WebPush\TTLExtension;
use WebPush\VAPID\VAPIDExtension;
use WebPush\VAPID\WebTokenProvider;
use WebPush\WebPush;
use WebPush\WebPushService;

class PushNotificationService implements WebPushService
{
    use LocatorAwareTrait;

    private WebPush $service;

    /**
     * Undocumented function
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $config = (array)Configure::read('WebPush.VAPID');
        // With Web-Token
        $jwsProvider = WebTokenProvider::create((string)$config['publicKey'], (string)$config['privateKey']);
        $vapidExtension = VAPIDExtension::create(
            (string)$config['subject'],
            $jwsProvider,
        );

        $payloadExtension = PayloadExtension::create()
            ->addContentEncoding(AES128GCM::create())
            ->addContentEncoding(AESGCM::create());

        $extensionManager = ExtensionManager::create()
            ->add(TTLExtension::create())
            ->add(TopicExtension::create())
            ->add($vapidExtension)
            ->add($payloadExtension);
        $client = new Client();
        $requestFactory = new RequestFactory();
        $this->service = WebPush::create($client, $requestFactory, $extensionManager);
    }

    /**
     * Send notification and delete expired
     *
     * @param \WebPush\NotificationInterface $notification Notification
     * @param \App\Model\Entity\PushSubscription $pushSubscription Subscription
     * @return \WebPush\StatusReportInterface|null
     * @throws \Cake\Core\Exception\CakeException
     * @throws \ErrorException
     */
    public function sendAndRemoveExpired(
        NotificationInterface $notification,
        PushSubscription $pushSubscription,
    ): ?StatusReportInterface {
        $subscription = $pushSubscription->toSubscription();
        if ($subscription != null) {
            $statusReport = $this->service->send($notification, $subscription);
            if (!$statusReport->isSuccess()) {
                if ($statusReport->isSubscriptionExpired()) {
                    $this->fetchTable('PushSubscriptions')->delete($pushSubscription);
                }
            }

            return $statusReport;
        }

        return null;
    }

    /**
     * Send notification
     *
     * @param \WebPush\NotificationInterface $notification Notification
     * @param \WebPush\SubscriptionInterface $subscription Subscription
     * @return \WebPush\StatusReportInterface
     */
    public function send(
        NotificationInterface $notification,
        SubscriptionInterface $subscription,
    ): StatusReportInterface {
        return $this->service->send($notification, $subscription);
    }

    /**
     * Create default message
     *
     * @param string $title Title
     * @param string|null $body Body
     * @return \WebPush\Message
     */
    public function createDefaultMessage(string $title, ?string $body = null): Message
    {
        return AngularPushMessage::create($title, $body);
    }
}
