<?php

use Carbon\Carbon;

if (!function_exists('totalTimeFormatted')) {
    function totalTimeFormatted($loginTime, $logoutTime)
    {
        $loginTime = Carbon::parse($loginTime);
        $logoutTime = Carbon::parse($logoutTime);

        $diff = $logoutTime->diff($loginTime);

        $days = $diff->d;
        $hours = $diff->h;
        $minutes = $diff->i;
        $seconds = $diff->s;

        return "{$days} days, {$hours} hours, {$minutes} minutes, {$seconds} seconds";
    }
}
