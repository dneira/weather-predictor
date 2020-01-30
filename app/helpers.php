<?php

if (!function_exists('is_valid_date')) {
    /**
     * @param string $date
     * @param string $format
     * @return bool
     */
    function is_valid_date(string $date, $format = 'Y-m-d H:i:s') : bool
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
