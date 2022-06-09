@component('mail::message')
# سلام

شما برای عضویت در تیم
**{{ $invitation->team->name }}**
دعوت شده اید.
به دلیل این که شما در سیستم ما عضویت ندارید، برای عضویت روی لینک
[عضویت] ({{ $url }})
کلیک کنید.
در صورت نداشتن تمایل این ایمیل را نادیده بگیرید

@component('mail::button', ['url' => $url])
عضویت
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
