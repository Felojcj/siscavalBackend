<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageValidated extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "SISCAVAL: Validacion Exitosa";
    public $msg;
    public $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public function __construct($password)
    // {
    //     $this->msg = $password;
    // }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.schedulevalidated');
    }
}
