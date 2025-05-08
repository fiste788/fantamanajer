<?php
declare(strict_types=1);

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;
use Override;

class Missing extends StreamSingleActivity implements StreamActivityInterface
{
    /**
     * Get body
     *
     * @return null
     */
    #[Override]
    public function getBody(): ?string
    {
        return null;
    }

    /**
     * Get title
     *
     * @return string
     */
    #[Override]
    public function getTitle(): string
    {
        return __('Your lineup for current matchday is missing. Come on!');
    }

    /**
     * Get icon
     *
     * @return string
     */
    #[Override]
    public function getIcon(): string
    {
        return 'star';
    }
}
