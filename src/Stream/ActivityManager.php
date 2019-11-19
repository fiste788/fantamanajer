<?php
declare(strict_types=1);

namespace App\Stream;

use StreamCake\Enrich;
use StreamCake\FeedManager;

class ActivityManager
{
    /**
     *
     * @param string $feedName FeedName
     * @param string $id id
     * @param bool $aggregated Aggregated
     * @param int $offset Offset
     * @param int $limit Limit
     * @param array $options Options
     * @return \App\Stream\StreamActivity[]
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
        $activities = $feed->getActivities($offset, $limit, $options);
        if ($aggregated) {
            $enriched = $enrich->enrichAggregatedActivities($activities['results']);
        } else {
            $enriched = $enrich->enrichActivities($activities['results']);
        }

        return $this->convertEnrichedToStreamActivity($enriched, $activities);
    }

    /**
     *
     * @param \StreamCake\EnrichedActivity[] $enricheds Ids
     * @param mixed $activities Activities
     * @return \App\Stream\StreamActivity[]
     */
    public function convertEnrichedToStreamActivity(array $enricheds, $activities): array
    {
        foreach ($enricheds as $key => $activity) {
            if (is_array($activity) || $activity->enriched()) {
                $activities['results'][$key] = $this->getFromVerb($activity->getNotEnrichedData());
            } else {
                unset($activities['results'][$key]);
            }
        }
        $activities['results'] = array_values($activities['results']);

        return $activities;
    }

    /**
     *
     * @param \StreamCake\EnrichedActivity[] $activity Activity
     * @return \App\Stream\StreamActivity
     */
    private function getFromVerb(array $activity): StreamActivity
    {
        $base = '\\App\\Stream\\Verb\\';
        if (array_key_exists('activities', $activity)) {
            $base .= 'Aggregated\\';
            $verb = $activity['verb'];
        } else {
            $verb = $activity->offsetGet('verb');
        }
        $className = $base . ucwords($verb);

        return new $className($activity);
    }
}
