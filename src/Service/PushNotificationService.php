<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Client;
use Laminas\Diactoros\RequestFactory;
use Minishlink\WebPush\ExtensionManager;
use Minishlink\WebPush\Notification;
use Minishlink\WebPush\Payload\AES128GCM;
use Minishlink\WebPush\Payload\AESGCM;
use Minishlink\WebPush\Payload\PayloadExtension;
use Minishlink\WebPush\StatusReport;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\TopicExtension;
use Minishlink\WebPush\TTLExtension;
use Minishlink\WebPush\VAPID\VAPIDExtension;
use Minishlink\WebPush\VAPID\WebTokenProvider;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\WebPushService;

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

        $config = (array)Configure::read('WebPush');
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
     * @param \Minishlink\WebPush\Notification $notification Notification
     * @param \Minishlink\WebPush\Subscription $subscription Subscription
     * @return \Minishlink\WebPush\StatusReport
     */
    public function send(Notification $notification, Subscription $subscription): StatusReport
    {
        return $this->service->send($notification, $subscription);
    }
}
