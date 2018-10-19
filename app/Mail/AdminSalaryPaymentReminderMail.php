<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminSalaryPaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($paymentDay, $paymentType)
    {
        $this->paymentDay = $paymentDay;
        $this->paymentType = $paymentType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin-salary-payment-reminder')
            ->with([
                'paymentDay' => $this->paymentDay,
                'paymentType' => $this->paymentType,
            ]);;
    }
}
