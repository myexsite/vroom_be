@component('mail::message')
# Reset Password

<p>Use the code below to update your password.
Click Forgot Password and then click 'I Have Reset Code' button.</p>
<h1>OTP</h1>
<p><b>OTP: </b><i>{{$token}}</i></p>


@component('mail::button', ['url' => 'http://localhost:8000/forgot-password'])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
