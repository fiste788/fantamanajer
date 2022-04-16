<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Core\Configure;
use WebPush\Message;

class AngularPushMessage extends Message
{
    /**
     * Undocumented function
     *
     * @param string $title Notification title
     * @param string|null $body Notification body
     * @return \WebPush\Message
     */
    public static function create(string $title, ?string $body = null): Message
    {
        /** @var array<string, string> $config  */
        $config = Configure::read('WebPushMessage.default');

        $message = new self($title, $body);
        /** @psalm-suppress LessSpecificReturnStatement */
        return $message
            ->withBadge($config['badge'])
            ->withIcon($config['icon'])
            ->withLang($config['lang'])
            ->withTimestamp(time());
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $options = [
            'title' => $this->getTitle(),
            'actions' => $this->getActions(),
            'body' => $this->getBody(),
            'dir' => $this->getDir(),
            'icon' => $this->getIcon(),
            'badge' => $this->getBadge(),
            'lang' => $this->getLang(),
            'renotify' => $this->getRenotify(),
            'requireInteraction' => $this->isInteractionRequired(),
            'tag' => $this->getTag(),
            'vibrate' => $this->getVibrate(),
            'silent' => $this->isSilent(),
            'data' => $this->getData(),
        ];

        return [
            'notification' => $this->getOptions($options),
        ];
    }

    /**
     * @param array<string, mixed> $properties Properties
     * @return array<string, mixed>
     */
    private function getOptions(array $properties): array
    {
        return array_filter($properties, static function ($v): bool {
            if (is_array($v) && count($v) === 0) {
                return false;
            }

            return $v !== null;
        });
    }
}
