<?php
declare(strict_types=1);

namespace App\Stream;

use Cake\I18n\DateTime;

interface StreamActivityInterface
{
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Get body
     *
     * @return string|null
     */
    public function getBody(): ?string;

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon(): string;

    /**
     * Get time
     *
     * @return \Cake\I18n\DateTime
     */
    public function getTime(): DateTime;
}
