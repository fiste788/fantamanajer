<?php
declare(strict_types=1);

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();

$schedule
    ->shell('database_backup.export -c gzip -f \'weekly' . DS . '{$DATABASE}_{$DATETIME}.sql.gz\' -r 5')
    ->mondays()
    ->at('02:00')
    ->description('Weekly backup')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'backup' . DS . date('ymd') . '.log');

return $schedule;
