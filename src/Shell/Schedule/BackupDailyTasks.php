<?php

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->shell('backup export -c gzip -f \'daily' . DS . '{$DATABASE}_{$DATETIME}.sql.gz\' -r 8')
    ->dailyAt('02:00')
    ->description('Daily backup')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'backup' . DS . date('ymd') . '.log');

return $schedule;
