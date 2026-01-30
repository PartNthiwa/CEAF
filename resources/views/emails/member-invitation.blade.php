<p>Hello,</p>

<p>You have been invited to register as a member.</p>

<p>
    Click this link to register:
    <a href="{{ $url }}">{{ $url }}</a>
</p>

@if($expiresAt)
    <p>This invitation expires on: {{ $expiresAt->format('d M Y, h:i A') }}</p>
@endif

<p>If you did not request this, you can ignore this email.</p>
