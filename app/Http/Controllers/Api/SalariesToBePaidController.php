<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use App\User;

class SalariesToBePaidController extends Controller
{
    public function index()
    {
        $salaries = User::employeeSalariesAndBonuses();
        
        $summedSalaries = round($salaries->sum('salary'));
        $summedBonuses = round($salaries->sum('bonus'));
        
        $period = collect(CarbonPeriod::create(now()->firstOfYear(),'1 month',now()->endOfYear()->firstOfMonth()));
        
        return $period->map(function($firstOfMonth) use ($summedSalaries,$summedBonuses) {
            
            $salaryPayDay = $firstOfMonth->lastOfMonth();

            if($this->isWeekend($salaryPayDay))
            {
                $salaryPayDay = $this->lastDayBeforeWeekend($salaryPayDay);
            }

            return [
                'Month' => $firstOfMonth->format('M'),
                'Salaries_payment_day' => $salaryPayDay->format('d'),
                'salaries_total' =>  $summedSalaries,
                'bonus_total' => $summedBonuses,
                'payments_total' => $summedBonuses + $summedSalaries
            ];
        });
      
    }
    
    private function isWeekend($carbonDay)
    {
        return in_array($carbonDay->format('l'),['Saturday','Friday']);
    }

    private function lastDayBeforeWeekend($weekendDay)
    {
        if($weekendDay->format('l') == 'Saturday')
        {
            return $weekendDay->subDays(2);
        }

        return $weekendDay->subDay();
    }
}
