<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use App\User;
use Illuminate\Support\Carbon;

class SalariesToBePaidController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['filter' => 'array']);

        $salaries = User::employeeSalariesAndBonuses();

        $summedSalaries = round($salaries->sum('salary'));
        $summedBonuses = round($salaries->sum('bonus'));

        $period = collect(CarbonPeriod::create(now()->firstOfYear(), '1 month', now()->endOfYear()->firstOfMonth()));

        return $period->map($this->mapSalariesForResponse($summedSalaries, $summedBonuses))
            ->when(isset($request->filter['month']), $this->filterByMonth())
            ->when(isset($request->filter['date']), $this->filterByDate());
    }

    private function mapSalariesForResponse($summedSalaries, $summedBonuses)
    {
        return function ($firstOfMonth) use ($summedSalaries, $summedBonuses) {
            return [
                'Month' => $firstOfMonth->format('M'),
                'Salaries_payment_day' => $this->salaryPayDay($firstOfMonth->lastOfMonth())->format('d'),
                'Bonus_payment_day' => $this->bonusPayDay(Carbon::parse('15th ' . $firstOfMonth->format('M')))->format('d'),
                'salaries_total' => $summedSalaries,
                'bonus_total' => $summedBonuses,
                'payments_total' => $summedBonuses + $summedSalaries
            ];
        };
    }

    private function salaryPayDay($lastDayInMonth)
    {
        if ($this->isWeekend($lastDayInMonth)) {
            return $lastDayInMonth->previous(Carbon::THURSDAY);
        }

        return $lastDayInMonth;
    }

    private function bonusPayDay($middleOfMonth)
    {
        if ($this->isWeekend($middleOfMonth)) {
            return $middleOfMonth->next(Carbon::THURSDAY);
        }

        return $middleOfMonth;
    }

    private function isWeekend($carbonDay)
    {
        return in_array($carbonDay->format('l'), ['Saturday', 'Friday']);
    }

    private function filterByMonth()
    {
        return function ($collection) {
            return $collection->filter(function ($salaries) {
                return str_contains($salaries['Month'], title_case(request()->filter['month']));
            });
        };
    }

    private function filterByDate()
    {
        return function ($collection) {
            $date = request()->filter['date'];

            return $collection->filter(function ($salaries) use ($date) {
                return $salaries['Salaries_payment_day'] == $date || $salaries['Bonus_payment_day'] == $date;
            });
        };
    }

}
