<?php

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
    protected $icon = null;
    
    /**
     * The notification image.
     *
     * @var string
     */
    protected $image = null;

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
    protected $badge = null;

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
    protected $lang = null;

    /**
     * The renotify.
     *
     * @var boolean
     */
    protected $renotify = true;

    /**
     * The renotify.
     *
     * @var boolean
     */
    protected $requireInteraction = true;

    /**
     * The tag for grouping
     *
     * @var tag
     */
    protected $tag = null;

    /**
     * The vibrate.
     *
     * @var integer[]
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
     * @param array $data
     *
     * @return static
     */
    public static function create($data = [])
    {
        return new static($data);
    }

    /**
     * Create new push message
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Set the notification title.
     *
     * @param  string $value
     * @return $this
     */
    public function title($value)
    {
        $this->title = $value;

        return $this;
    }

    /**
     * Set the notification body.
     *
     * @param  string $value
     * @return $this
     */
    public function body($value)
    {
        $this->body = $value;

        return $this;
    }

    /**
     * Set the notification icon.
     *
     * @param  string $value
     * @return $this
     */
    public function icon($value)
    {
        $this->icon = $value;

        return $this;
    }
    
    /**
     * Set the notification image.
     *
     * @param  string $value
     * @return $this
     */
    public function image($value)
    {
        $this->image = $value;

        return $this;
    }

    /**
     * Set an action.
     *
     * @param  string $title
     * @param  string $action
     * @return $this
     */
    public function action($title, $action, $icon = '')
    {
        $this->actions[] = compact('title', 'action', 'icon');

        return $this;
    }

    /**
     * Set the badge.
     *
     * @param type $badge
     * @return $this
     */
    public function badge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Set the direction.
     *
     * @param type $dir
     * @return $this
     */
    public function dir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Set the language.
     *
     * @param string $lang
     * @return $this
     */
    public function lang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Set the renotify
     *
     * @param bool $renotify
     * @return $this
     */
    public function renotify($renotify)
    {
        $this->renotify = $renotify;

        return $this;
    }

    /**
     * Set the require interaction.
     *
     * @param bool $requireInteraction
     * @return $this
     */
    public function requireInteraction($requireInteraction)
    {
        $this->requireInteraction = $requireInteraction;

        return $this;
    }

    /**
     * Set the tag.
     *
     * @param type $tag
     * @return $this
     */
    public function tag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Set the vibrate times.
     *
     * @param array $vibrate
     * @return $this
     */
    public function vibrate($vibrate)
    {
        $this->vibrate = $vibrate;

        return $this;
    }

    /**
     * Set the data array
     *
     * @param array $data
     * @return $this
     */
    public function data($data = [])
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Serialize the message
     *
     * @return string
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
                'data' => $this->data
            ]
        ];
    }
}
