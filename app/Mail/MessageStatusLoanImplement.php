<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageStatusLoanImplement extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Estado Implemento del Prestamo";
    public $msg;
    public $status;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($loan_mail_implement,$status)
    {
        $this->msg = $loan_mail_implement;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.status_loan_implement');
    }
}
