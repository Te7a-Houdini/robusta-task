<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use App\User;
use Illuminate\Support\Carbon;

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
            $bonusPayDay = Carbon::parse('15th '. $firstOfMonth->format('M'));
             
            
            if($this->isWeekend($salaryPayDay))
            {
                $salaryPayDay = $salaryPayDay->previous(Carbon::THURSDAY);
            }

            if($this->isWeekend($bonusPayDay))
            {
                $bonusPayDay = $bonusPayDay->next(Carbon::THURSDAY);
            }

            return [
                'Month' => $firstOfMonth->format('M'),
                'Salaries_payment_day' => $salaryPayDay->format('d'),
                'Bonus_payment_d' => $bonusPayDay->format('d'),
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

}
