<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event[]|\Cake\Collection\CollectionInterface $events
 */
?>
<?php /**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event[]|\Cake\Collection\CollectionInterface $events
 */


foreach ($events as $event) {
    
// Remove & escape any HTML to make sure the feed content will validate.
    $body = h(strip_tags($event->body));
    $body = $this->Text->truncate($body, 400, [
        'ending' => '...',
        'exact'  => true,
        'html'   => true,
    ]);

    echo  $this->Rss->item([], [
        'title' => $event->title,
        'link' => $event->link,
        'guid' => ['url' => $event->link, 'isPermaLink' => 'true'],
        'description' => $body,
        'pubDate' => $event->created_at
    ]);
}
