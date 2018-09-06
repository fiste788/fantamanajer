<?php
namespace App\View\Cell;

use Cake\View\Cell;
use StreamCake\Enrich;
use StreamCake\FeedManager;

class StreamCell extends Cell
{
    public function display($feedName, $id, $aggregated = false)
    {
        $feedManager = new FeedManager();
        $feed = $feedManager->getFeed($feedName, $id);
        $enrich = new Enrich();
        if ($aggregated) {
            $enriched = $enrich->enrichAggregatedActivities($feed->getActivities()['results']);
        } else {
            $enriched = $enrich->enrichActivities($feed->getActivities()['results']);
        }

        $this->set([
            'stream' => $enriched,
            '_serialize' => false
        ]);
    }
}
