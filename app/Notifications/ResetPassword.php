<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as Notification;

class ResetPassword extends Notification
{
    public function toMail($notifiable)
    {
        $url = url(config('app.client_url').'/password/reset/'.$this->token).'?email='.urlencode($notifiable->email);
        return (new MailMessage)
                    ->line('شما به این دلیل این ایمیل را دریافت کرده اید چون درخواست بازیابی ایمیل داشته اید  برای حساب کاربری تان')
                    ->action('بازیابی رمز عبور', $url)
                    ->line('اگر شما درخواست بازیابی رمز عبور نداشتید لطفا این ایمیل را نادیده بگیرید');
    }
}
