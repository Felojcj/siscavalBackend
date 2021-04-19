<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $urlToResetForm = "http://localhost:4200/password/reset/". $this->token;
        return (new MailMessage)
            ->subject(Lang::get('Bienvenido a SALIRES (Sistema de Reservas del Poli)'))
            ->action(Lang::get('Reset Password'), $urlToResetForm)
            ->line(Lang::get('Este link expirará en :count minutos.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::get('Si no solicitó un restablecimiento de contraseña, no se requiere ninguna otra acción.. Token: ==>'. $this->token));
    }

    
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
