<?php
declare(strict_types=1);

namespace App\Service\Rating;

use App\Model\Entity\Matchday;
use Cake\Console\ConsoleIo;

interface RatingSourceInterface
{
    public function setIo(?ConsoleIo $io): void;
    public function getRatings(Matchday $matchday, int $offsetGazzetta = 0, bool $forceDownload = false): ?string;
}