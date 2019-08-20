<?php
declare(strict_types=1);

namespace App\Stream;

interface StreamActivityInterface
{
    public function getTitle();

    public function getBody();

    public function getIcon();

    public function getTime();
}
