<?php
declare(strict_types=1);

namespace App\Stream;

use StreamCake\Enrich;
use StreamCake\EnrichedActivity;
use StreamCake\FeedManager;

class ActivityManager
{
    /**
     * @param string $feedName FeedName
     * @param string $id id
     * @param bool $aggregated Aggregated
     * @param int $offset Offset
     * @param int $limit Limit
     * @param array $options Options
     *
     * @return array[]
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function getActivities(
        string $feedName,
        string $id,
        bool $aggregated,
        int $offset = 0,
        int $limit = 20,
        array $options = []
    ): array {
        $feedManager = new FeedManager();
        $feed = $feedManager->getFeed($feedName, $id);
        $enrich = new Enrich();

        /** @var array<string, array> $activities */
        $activities = (array)$feed->getActivities($offset, $limit, $options);
        if ($aggregated) {
            $enriched = $enrich->enrichAggregatedActivities($activities['results']);
        } else {
            $enriched = $enrich->enrichActivities($activities['results']);
        }

        return $this->convertEnrichedToStreamActivity($enriched, $activities);
    }

    /**
     * @param \StreamCake\EnrichedActivity[] $enricheds Ids
     * @param array<string, mixed> $activities Activities
     *
     * @return array<string, mixed>
     *
     */
    public function convertEnrichedToStreamActivity(array $enricheds, $activities): array
    {
        foreach ($enricheds as $key => $activity) {
            /** @var \StreamCake\EnrichedActivity $activity */
            if ($activity->enriched()) {
                /** @psalm-suppress MixedArrayAssignment */
                $activities['results'][$key] = $this->getFromVerb($activity);
            } else {
                /** @psalm-suppress MixedArrayAccess */
                unset($activities['results'][$key]);
            }
        }
        $activities['results'] = array_values((array)$activities['results']);

        return $activities;
    }

    /**
     *
     * @param \StreamCake\EnrichedActivity $activity Activity
     * @return \App\Stream\StreamActivity|null
     */
    private function getFromVerb(EnrichedActivity $activity): ?StreamActivity
    {
        $namespace = '\\App\\Stream\\Verb\\' . ($activity->offsetExists('activities') ?: 'Aggregated\\');
        $name = (string)($activity->offsetGet('verb') ?? '');
        $className = $namespace . ucwords($name);

        /**
         * @var \App\Stream\StreamActivity|null $clazz
         * @psalm-suppress MixedMethodCall
         */
        $clazz = class_exists($className) ? new $className($activity) : null;

        return $clazz;
    }
}
