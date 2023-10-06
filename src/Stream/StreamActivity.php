<?php
declare(strict_types=1);

namespace App\Stream;

use Cake\I18n\DateTime;
use StreamCake\EnrichedActivity;

/**
 * Event Entity.
 */
abstract class StreamActivity implements StreamActivityInterface
{
    /**
     * @var \StreamCake\EnrichedActivity $activity
     */
    protected EnrichedActivity $activity;

    /**
     * @var \Cake\I18n\DateTime
     */
    protected DateTime $timeStamp;

    /**
     * @var string $title
     */
    public string $title;

    /**
     * @var string
     */
    public string $time;

    /**
     * @var string $humanTime
     */
    public string $humanTime;

    /**
     * @var string $icon
     */
    public string $icon;

    /**
     * @var string|null $body
     */
    public ?string $body = null;

    /**
     * @param \StreamCake\EnrichedActivity $activity Activity
     */
    public function __construct(EnrichedActivity $activity)
    {
        $this->activity = $activity;
        $this->body = $this->getBody();
        $this->title = $this->getTitle();
        $this->timeStamp = $this->getTime();
        $this->time = $this->timeStamp->toIso8601String();
        $this->humanTime = $this->timeStamp->timeAgoInWords();
        $this->icon = $this->getIcon();
    }

    /**
     * Get contain
     *
     * @return array<string>
     */
    public static function contain(): array
    {
        return [];
    }
}
