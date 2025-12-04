<?php

// app/Helpers/TimeHelper.php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    /**
     * Get current time in Asia/Jakarta timezone
     */
    public static function now()
    {
        return Carbon::now('Asia/Jakarta');
    }

    /**
     * Parse time to Asia/Jakarta timezone
     */
    public static function parse($time)
    {
        return Carbon::parse($time, 'Asia/Jakarta');
    }

    /**
     * Get today date in Asia/Jakarta
     */
    public static function today()
    {
        return Carbon::today('Asia/Jakarta');
    }

    /**
     * Format time for display
     */
    public static function format($time, $format = 'Y-m-d H:i:s')
    {
        return Carbon::parse($time, 'Asia/Jakarta')->format($format);
    }
}
