@component('mail::message')
# Welcome to MultiAuth

You Can Complete the Registration By Clicking the below Button {{ $user->name }}
@component('mail::button', ['url' => route('user.activate', $user['token'])])
Activate
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
