<?php

declare(strict_types=1);

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();

$schedule
    ->shell('weekly_script -n')
    ->every('minute', 6)
    ->between('09:00', '22:00')
    ->description('Weekly script')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'weekly_script' . DS . date('ymd') . '.log');

return $schedule;
