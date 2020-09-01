<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Email</title>
    <style>
    .container {
        text-align: center;
        font-size: 19px;
    }
    .title {
        font-size: 30px;
    }
    .description {
        margin-bottom: 30px;
    }
    .btn {
        padding: 10px 20px;
        margin: 20px 0;
        background-color: #fff;
        color: #000;
        border: 2px solid #000;
        border-radius: 5px;
        text-decoration: none;
        transition-duration: 0.3s;
    }
    .btn:hover {
        background-color: #101010;
        color: #fff;
    }
    .email-footer {
        color: #707070;
        margin-top: 30px;
    }
    </style>
</head>
<body>
<div class="container">
    <h2 class="title">Reset Password</h2>
    <div class="body">
        <div class="description">
            You are receiving this email because we received a password reset request for your account.
        </div>
        <a
                href="{{ route('auth.password.redirect', ['token' => $token, 'email' => $email]) }}"
                class="btn reset-pass-button">
            Reset Password
        </a>
    </div>
    <div class="email-footer">
        <div class="expired-time">
            This password reset link will expire in {{ config('auth.passwords.users.expire') }} minutes.
        </div>
        <div class="message">
            If you did not request a password reset, no further action is required.
        </div>
    </div>
</div>
</body>
</html>
