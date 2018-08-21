<?php

namespace App\Stream;

use StreamCake\Enrich;
use StreamCake\FeedManager;

class ActivityManager {
    
    /**
     * 
     * @param string $feedName
     * @param int $id
     * @param boolean $aggregated
     */
    public function getActivities($feedName, $id, $aggregated)
    {
        $feedManager = new FeedManager();
        $feed = $feedManager->getFeed($feedName, $id);
        $enrich = new Enrich();
        if($aggregated) {
            $enriched = $enrich->enrichAggregatedActivities($feed->getActivities()['results']);
        } else {
            $enriched = $enrich->enrichActivities($feed->getActivities()['results']);
        }
        return $this->convertEnrichedToStreamActivity($enriched);
     }
    
    /**
     * 
     * @param \StreamCake\ActivityInterface[] $activities
     * @return \App\Stream\StreamActivity[]
     */
     public function convertEnrichedToStreamActivity($activities)
     {
        $streamActivities = [];
        foreach ($activities as $activity) {
            $streamActivities[] = $this->getFromVerb($activity);
        }
        return $streamActivities;
     }
     
     /**
      * 
      * @param \StreamCake\EnrichedActivity[] $activity
      * @return StreamActivity
      */
     private function getFromVerb($activity) {
        $base = '\\App\\Stream\\Verb\\';
        if (array_key_exists('activities',$activity)) {
            $base .= 'Aggregated\\';
            $verb = $activity['verb'];
        } else {
            $verb = $activity->offsetGet('verb');
        }
        $className = $base . $verb;
        return new $className($activity);
     }
}
