<?php
declare(strict_types=1);

namespace App\View;

use Cake\View\JsonView;
use Override;

class CustomFallbackView extends JsonView
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function contentType(): string
    {
        return static::TYPE_MATCH_ALL;
    }
}
