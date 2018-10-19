<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\SalaryPaymentCalculator;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminSalaryPaymentReminderMail;

class AdminSalariesPaymentReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $salaryPayDay = SalaryPaymentCalculator::salaryPayDay(now());
        $bonusPayDay = SalaryPaymentCalculator::bonusPayDay(now());
        $todaySubTwoDays = now()->subDays(2);

        $admin = User::role('admin')->first();

        if ($todaySubTwoDays->eq($salaryPayDay)) {
            Mail::to($admin)->send(new AdminSalaryPaymentReminderMail(
                $salaryPayDay->format('d') . ' ' . $salaryPayDay->format('M'),
                'Base'
            ));
        }

        if ($todaySubTwoDays->eq($bonusPayDay)) {
            Mail::to($admin)->send(new AdminSalaryPaymentReminderMail(
                $bonusPayDay->format('d') . ' ' . $bonusPayDay->format('M'),
                'Bonus'
            ));
        }
    }


}
