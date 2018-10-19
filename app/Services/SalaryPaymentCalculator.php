<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class SalaryPaymentCalculator
{
    public static function salaryPayDay($lastDayInMonth)
    {
        if (static::isWeekend($lastDayInMonth)) {
            return $lastDayInMonth->previous(Carbon::THURSDAY);
        }

        return $lastDayInMonth;
    }

    public static function bonusPayDay($middleOfMonth)
    {
        if (static::isWeekend($middleOfMonth)) {
            return $middleOfMonth->next(Carbon::THURSDAY);
        }

        return $middleOfMonth;
    }

    public static function isWeekend($carbonDay)
    {
        return in_array($carbonDay->format('l'), ['Saturday', 'Friday']);
    }
}