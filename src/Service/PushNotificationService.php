<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Client;
use Laminas\Diactoros\RequestFactory;
use WebPush\ExtensionManager;
use WebPush\Notification;
use WebPush\Payload\AES128GCM;
use WebPush\Payload\AESGCM;
use WebPush\Payload\PayloadExtension;
use WebPush\StatusReport;
use WebPush\Subscription;
use WebPush\TopicExtension;
use WebPush\TTLExtension;
use WebPush\VAPID\VAPIDExtension;
use WebPush\VAPID\WebTokenProvider;
use WebPush\WebPush;
use WebPush\WebPushService;

/**
 * @property \App\Model\Table\PushSubscriptionsTable $PushSubscriptions
 */
class PushNotificationService implements WebPushService
{
    use ModelAwareTrait;

    private WebPush $service;

    /**
     * Undocumented function
     *
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $this->loadModel('PushSubscriptions');

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
     * @param \WebPush\Notification $notification Notification
     * @param \WebPush\Subscription $subscription Subscription
     * @return \WebPush\StatusReport
     */
    public function send(Notification $notification, Subscription $subscription): StatusReport
    {
        return $this->service->send($notification, $subscription);
    }
}
