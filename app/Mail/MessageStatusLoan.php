<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageStatusLoan extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Estado Prestamo";
    public $msg;
    public $status;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($loan_mail,$status)
    {
        $this->msg = $loan_mail;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.status_loan');
    }
}
