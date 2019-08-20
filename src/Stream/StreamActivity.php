<?php
declare(strict_types=1);

namespace App\Stream;

/**
 * Event Entity.
 *
 */
abstract class StreamActivity implements StreamActivityInterface
{
    /**
     *
     * @var \StreamCake\ActivityInterface $activity
     */
    protected $activity;

    /**
     *
     * @var \Cake\I18n\Time
     */
    protected $timeStamp;

    /**
     *
     * @var String $text
     */
    public $title;

    /**
     *
     * @var string
     */
    public $time;

    /**
     *
     * @var String $humanTime
     */
    public $humanTime;

    /**
     *
     * @var String $icon
     */
    public $icon;

    /**
     *
     * @var String $body
     */
    public $body;

    /**
     *
     * @param \StreamCake\EnrichedActivity $activity
     */
    public function __construct($activity)
    {
        $this->activity = $activity;
        $this->body = $this->getBody();
        $this->title = $this->getTitle();
        $this->timeStamp = $this->getTime();
        $this->time = $this->timeStamp->toIso8601String();
        $this->humanTime = $this->timeStamp->timeAgoInWords();
        $this->icon = $this->getIcon();
    }

    public static function contain()
    {
        return [];
    }
}
