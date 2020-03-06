<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Datasource\ModelAwareTrait;
use Cake\Log\Log;

/**
 *
 * @property \App\Model\Table\PushSubscriptionsTable $PushSubscriptions
 */
class PushNotificationService
{
    use ModelAwareTrait;

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->loadModel('PushNotifications');
    }

    /**
     * Remove expired push subscriptions
     *
     * @param \Minishlink\WebPush\MessageSentReport[] $report Report
     *
     * @return int
     */
    public function deleteExpired($report): int
    {
        $expired = [];

        /** @var \Minishlink\WebPush\MessageSentReport $result */
        foreach ($report as $result) {
            $response = $result->getResponse();

            if ($result->isSuccess()) {
                // process successful message sent
                Log::info(sprintf(
                    'Notification with payload %s successfully sent for endpoint %s.',
                    $response != null ? $response->getBody()->__toString() : '',
                    $result->getEndpoint()
                ));
            } else {
                // or a failed one - check expiration first
                if ($result->isSubscriptionExpired()) {
                    // this is just an example code, not included in library!
                    Log::info(sprintf('Expired %s', $result->getEndpoint()));
                    $expired[] = $result->getEndpoint();
                } else {
                    // process faulty message
                    Log::info(sprintf(
                        'Notification failed: %s. Payload: %s, endpoint: %s',
                        $result->getReason(),
                        $response != null ? $response->getBody()->__toString() : '',
                        $result->getEndpoint()
                    ));
                }
            }
        }
        //$this->PushSubscriptions->updateAll(['expired' => true], ['id' => $expired]);
        return $this->PushSubscriptions->deleteAll(['endpoint IN' => $expired]);
    }
}
