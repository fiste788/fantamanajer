<?php
declare(strict_types=1);

namespace StreamCake;

interface EnrichInterface
{
    /**
     * @param array $activities
     *
     * @return \StreamCake\EnrichedActivity[]
     */
    public function enrichActivities(array $activities);

    /**
     * @param array $aggregatedActivities
     *
     * @return \StreamCake\EnrichedActivity[]
     */
    public function enrichAggregatedActivities(array $aggregatedActivities);
}
