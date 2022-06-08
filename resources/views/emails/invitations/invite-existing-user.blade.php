@component('mail::message')
    # سلام

    شما برای عضویت در تیم
    **{{ $invitation->team->name }}**
    دعوت شده اید.
    شما در سایت ما عضویت دارید میتوانید برای قبول عضویت به داشبورد خود بروید
    [داشبورد] ({{ $url }})

    @component('mail::button', ['url' => $url])
        داشبورد
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
