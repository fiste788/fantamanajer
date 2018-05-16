<?php

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->shell('fantamanajer push_notification missing_lineup -n')
    ->every('minute', 6)
    ->description('Missing lineup notification')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'missing_lineup_notification' . DS . date('yyyyddmm') . '.log');

return $schedule;
