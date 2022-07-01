<?php

namespace App\Notifications\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationToken extends Notification
{
    use Queueable;

    private $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
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
        if(isset($this->details['data'])){
            $url = $this->details['data'];
        }else{
            $verification = isset($this->details['verification']) && $this->details['verification'] ? $this->details['verification'] : '';
            $url = config('app.http_shema_main') . config('app.main_domain') . '/login?verification_token=' . $verification . '&email='. $this->details['email'];
        }

        return (new MailMessage)
                    ->subject('Код верификации')
                    ->greeting('Приветствуем!')
                    ->line('Ссылка для подтверждения Вашего email:')
                    ->action('Авторизоваться', $url);
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
