<?php
declare(strict_types=1);

namespace App\Stream;

use StreamCake\EnrichedActivity;

/**
 * Event Entity.
 *
 */
abstract class StreamActivity implements StreamActivityInterface
{
    /**
     *
     * @var \StreamCake\EnrichedActivity $activity
     */
    protected $activity;

    /**
     *
     * @var \Cake\I18n\FrozenTime
     */
    protected $timeStamp;

    /**
     *
     * @var string $title
     */
    public $title;

    /**
     *
     * @var string
     */
    public $time;

    /**
     *
     * @var string $humanTime
     */
    public $humanTime;

    /**
     *
     * @var string $icon
     */
    public $icon;

    /**
     *
     * @var string|null $body
     */
    public $body;

    /**
     *
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
     * @return array
     */
    public static function contain(): array
    {
        return [];
    }
}
