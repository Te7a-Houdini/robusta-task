<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use App\User;
use Illuminate\Support\Carbon;
use App\Jobs\AdminSalariesPaymentReminder;
use App\Services\SalaryPaymentCalculator;

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
                'Salaries_payment_day' => SalaryPaymentCalculator::salaryPayDay($firstOfMonth)->format('d'),
                'Bonus_payment_day' => SalaryPaymentCalculator::bonusPayDay($firstOfMonth)->format('d'),
                'salaries_total' => $summedSalaries,
                'bonus_total' => $summedBonuses,
                'payments_total' => $summedBonuses + $summedSalaries
            ];
        };
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
