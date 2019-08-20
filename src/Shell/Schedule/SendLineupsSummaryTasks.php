<?php
declare(strict_types=1);

use CakeScheduler\Schedule\CakeSchedule;

$schedule = new CakeSchedule();
$schedule
    ->shell('send_lineups_email -n')
    ->every('minute', 3)
    ->description('Send lineup summary')
    ->appendOutputTo(LOGS . 'schedule' . DS . 'send_lineup_summary' . DS . date('ymd') . '.log');

return $schedule;
