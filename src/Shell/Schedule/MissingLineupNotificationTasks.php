<?php

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->shell('send_missing_lineup_notification -n')
    ->every('minute', 15)
    ->description('Missing lineup notification')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'missing_lineup_notification' . DS . date('ymd') . '.log');

return $schedule;
