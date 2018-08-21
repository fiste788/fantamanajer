<?=
$event->icon = 'message';
$event->title = $activity->offsetGet('actor')->name . ' ha rilasciato una conferenza stampa intitolata ' . $activity->offsetGet('object')->title;
$event->body = $activity->offsetGet('object')->body;

