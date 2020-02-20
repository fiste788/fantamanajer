<?php
declare(strict_types=1);

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();

/** @psalm-suppress MixedMethodCall */
$schedule
    ->shell('database_backup.export -c gzip -f \'yearly' . DS . '{$DATABASE}_{$DATETIME}.sql.gz\'')
    ->month(7)
    ->dayOfMonth(31)
    ->at('02:00')
    ->description('Yearly backup')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'backup' . DS . date('ymd') . '.log');

return $schedule;
