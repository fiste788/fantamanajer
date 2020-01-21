<?php
declare(strict_types=1);

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();

/** @psalm-suppress MixedMethodCall */
$schedule
    ->shell('backup export -c gzip -f \'monthly' . DS . '{$DATABASE}_{$DATETIME}.sql.gz\' -r 13')
    ->monthly()
    ->at('02:00')
    ->description('Monthly backup')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'backup' . DS . date('ymd') . '.log');

return $schedule;
