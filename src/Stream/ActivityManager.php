<?php

namespace App\Stream;

use StreamCake\Enrich;
use StreamCake\FeedManager;

class ActivityManager
{

    /**
     *
     * @param string $feedName
     * @param int $id
     * @param bool $aggregated
     */
    public function getActivities($feedName, $id, $aggregated, $offset = 0, $limit = 20, $options = [])
    {
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
     * @param \StreamCake\ActivityInterface[] $enricheds
     * @return \App\Stream\StreamActivity[]
     */
    public function convertEnrichedToStreamActivity($enricheds, $activities)
    {
        foreach ($enricheds as $key => $activity) {
            $activities['results'][$key] = $this->getFromVerb($activity);
        }

        return $activities;
    }

    /**
     *
     * @param \StreamCake\EnrichedActivity[] $activity
     * @return StreamActivity
     */
    private function getFromVerb($activity)
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
