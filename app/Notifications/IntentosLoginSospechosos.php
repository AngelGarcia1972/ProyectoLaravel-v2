<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IntentosLoginSospechosos extends Notification
{
    use Queueable;

    public string $ip;

    public function __construct(string $ip)
    {
        $this->ip = $ip;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Intentos de inicio de sesión sospechosos')
            ->greeting('Hola ' . $notifiable->name)
            ->line('Se han detectado 3 o más intentos fallidos de inicio de sesión en tu cuenta.')
            ->line('Dirección IP del atacante: **' . $this->ip . '**')
            ->line('Si fuiste tú, ignora este mensaje. Si no reconoces esta actividad, cambia tu contraseña inmediatamente.')
            ->action('Ir a mi perfil', url('/perfil'))
            ->salutation('Saludos, ' . config('app.name'));
    }
}
