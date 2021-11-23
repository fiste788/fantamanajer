<?php
declare(strict_types=1);

namespace App\Service;

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
            $jwsProvider
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
     * Send notification
     *
     * @param \WebPush\NotificationInterface $notification Notification
     * @param \WebPush\SubscriptionInterface $subscription Subscription
     * @return \WebPush\StatusReportInterface
     */
    public function send(
        NotificationInterface $notification,
        SubscriptionInterface $subscription
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
        /** @var array<string, string> $config  */
        $config = Configure::read('WebPushMessage.default');

        return Message::create($title, $body)
            ->withBadge($config['badge'])
            ->withIcon($config['icon'])
            ->withLang($config['lang']);
    }
}
