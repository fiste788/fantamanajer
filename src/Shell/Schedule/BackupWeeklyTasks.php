<?php

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->shell('backup export -r 5 -c gzip -f weekly' . DS . '{$DATABASE}_{$DATETIME}.sql.gz')
    ->weeklyOn(0, '02:00')
    ->description('Weekly backup')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'backup' . DS . date('ymd') . '.log');

return $schedule;
