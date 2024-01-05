<?php
declare(strict_types=1);

namespace App\View;

use Cake\View\JsonView;

class CustomFallbackView extends JsonView
{
    /**
     * @inheritDoc
     */
    public static function contentType(): string
    {
        return static::TYPE_MATCH_ALL;
    }
}
