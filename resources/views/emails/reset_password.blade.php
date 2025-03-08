<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h1>Hello {{ $user->name }},</h1>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>Your password reset token is: <strong>{{ $token }}</strong></p>
    <p>This token will expire in 60 minutes.</p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>Regards,<br>The {{ config('app.name') }} Team</p>
</body>
</html>
