@component('mail::message')
    <h1>We have received your request to reset your account password</h1>
    <h5>You can use the following code to recover your account:</h5>
          <h3>THE CODE </h3>
    <h2 style="justify-content: center">
    @component('mail::panel')
        {{ $code }}
    @endcomponent
    </h2>
    <h5>The allowed duration of the code is one day from the time the message was sent</h5>
@endcomponent
