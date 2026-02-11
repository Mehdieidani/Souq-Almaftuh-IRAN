<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Ad;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // هر دقیقه آگهی‌های منقضی شده را به وضعیت عادی برمی‌گرداند
        $schedule->call(function () {
            Ad::where('type', '!=', 'normal')
              ->where('expires_at', '<', now())
              ->update(['type' => 'normal', 'expires_at' => null]);
        })->everyMinute();
    }

    // ...
}