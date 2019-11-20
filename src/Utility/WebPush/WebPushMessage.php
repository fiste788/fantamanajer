<?php

declare(strict_types=1);

namespace App\Utility\WebPush;

use JsonSerializable;

class WebPushMessage implements JsonSerializable
{
    /**
     * The notification title.
     *
     * @var string
     */
    protected $title;

    /**
     * The notification body.
     *
     * @var string
     */
    protected $body;

    /**
     * The notification icon.
     *
     * @var string
     */
    protected $icon;

    /**
     * The notification image.
     *
     * @var string
     */
    protected $image;

    /**
     * The notification actions.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * The badge icon.
     *
     * @var string
     */
    protected $badge;

    /**
     * The text direction.
     *
     * @var string
     */
    protected $dir = 'auto';

    /**
     * The language.
     *
     * @var string
     */
    protected $lang;

    /**
     * The renotify.
     *
     * @var bool
     */
    protected $renotify = true;

    /**
     * The renotify.
     *
     * @var bool
     */
    protected $requireInteraction = true;

    /**
     * The tag for grouping
     *
     * @var string
     */
    protected $tag;

    /**
     * The vibrate.
     *
     * @var int[]
     */
    protected $vibrate = [300, 200, 300];

    /**
     * The data object.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Create new push message
     *
     * @param array $data Datas
     *
     * @return static
     */
    public static function create(array $data = []): WebPushMessage
    {
        return new static($data);
    }

    /**
     * Create new push message
     *
     * @param array $data Datas
     * @return void
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Set the notification title
     *
     * @param string $title
     * @return WebPushMessage
     */
    public function title(string $title): WebPushMessage
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the notification body.
     *
     * @param  string $value Body text
     * @return $this
     */
    public function body(string $value): WebPushMessage
    {
        $this->body = $value;

        return $this;
    }

    /**
     * Set the notification icon.
     *
     * @param  string $value Icon path
     * @return $this
     */
    public function icon(string $value): WebPushMessage
    {
        $this->icon = $value;

        return $this;
    }

    /**
     * Set the notification image.
     *
     * @param  string $value Image path
     * @return $this
     */
    public function image(string $value): WebPushMessage
    {
        $this->image = $value;

        return $this;
    }

    /**
     * Set an action.
     *
     * @param  string $title Title
     * @param  string $action Action
     * @param  string $icon Icon
     * @return $this
     */
    public function action(string $title, string $action, string $icon = ''): WebPushMessage
    {
        $this->actions[] = compact('title', 'action', 'icon');

        return $this;
    }

    /**
     * Set the badge.
     *
     * @param string $badge Badge icon
     * @return $this
     */
    public function badge(string $badge): WebPushMessage
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Set the direction.
     *
     * @param string $dir Text direction
     * @return $this
     */
    public function dir(string $dir): WebPushMessage
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Set the language.
     *
     * @param string $lang Language
     * @return $this
     */
    public function lang(string $lang): WebPushMessage
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Set the renotify
     *
     * @param bool $renotify Renotify
     * @return $this
     */
    public function renotify(bool $renotify): WebPushMessage
    {
        $this->renotify = $renotify;

        return $this;
    }

    /**
     * Set the require interaction.
     *
     * @param bool $requireInteraction RequireInteraction
     * @return $this
     */
    public function requireInteraction(bool $requireInteraction): WebPushMessage
    {
        $this->requireInteraction = $requireInteraction;

        return $this;
    }

    /**
     * Set the tag.
     *
     * @param string $tag Tag
     * @return $this
     */
    public function tag(string $tag): WebPushMessage
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Set the vibrate times.
     *
     * @param array $vibrate Vibration timings
     * @return $this
     */
    public function vibrate(array $vibrate): WebPushMessage
    {
        $this->vibrate = $vibrate;

        return $this;
    }

    /**
     * Set the data array
     *
     * @param array $data Additional data
     * @return $this
     */
    public function data(array $data = []): WebPushMessage
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Serialize the message
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'notification' => [
                'title' => $this->title,
                'actions' => $this->actions,
                'body' => $this->body,
                'dir' => $this->dir,
                'icon' => $this->icon,
                'image' => $this->image,
                'badge' => $this->badge,
                'lang' => $this->lang,
                'renotify' => $this->renotify,
                'requireInteraction' => $this->requireInteraction,
                'tag' => $this->tag,
                'vibrate' => $this->vibrate,
                'data' => $this->data,
            ],
        ];
    }
}
