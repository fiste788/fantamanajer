<?php
/**
 * @var \StreamCake\EnrichedActivity[] $stream
 */
$events = [];
foreach($stream as &$activity) {
    $event = new stdClass();
    if (is_array($activity) || $activity->enriched()) {
        if (isset($prefix)) {
            if ($activity->offsetExists('activities')) {
                $time = $activity->offsetGet('updated_at');
                $this->element("AggregatedActivity/{$prefix}_{$activity->offsetGet('verb')}", ['activity' => $activity, 'event' => $event]);
            } else {
                $time = $activity->offsetGet('time');
                $this->element("Activity/{$prefix}_{$activity->offsetGet('verb')}", ['activity' => $activity, 'event' => $event]);
            }
        } else {
            if (array_key_exists('activities', $activity)) {
                $time = $activity['updated_at'];
                $this->element("AggregatedActivity/{$activity['verb']}", ['activity' => $activity, 'event' => $event]);
            } else {
                $time = $activity->offsetGet('time');
                $this->element("Activity/{$activity->offsetGet('verb')}", ['activity' => $activity, 'event' => $event]);
            }
        }
        $event->humanTime = $time->diffForHumans();
        $event->time = $time->toIso8601String();
        $events[] = $event;
    } else {
        $this->warning('The activity could not be rendered, the following field/refs could not be enriched:', $activity->getNotEnrichedData());
    }
}
echo json_encode($events);