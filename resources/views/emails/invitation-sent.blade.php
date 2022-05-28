@component('mail::message')
  Hello {{ $invitation->name }} <br>
  We invite you to join {{ config('app.name') }}, <br>
  This invitation will expire at {{ $invitation->expired_at->format('d-m-Y') }}

  @component('mail::button', ['url' => $url])
    Accept
  @endcomponent

  Thanks,<br>
  {{ config('app.name') }}
@endcomponent
