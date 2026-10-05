<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('monitoring:reminder')
    ->weeklyOn(
        5,
        '16:00'
    )
    ->timezone('Asia/Makassar');