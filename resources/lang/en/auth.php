<?php

return [
    'credentials' => [
        'invalid' => 'your email or password was incorrect, please try again'
    ],
    'messages' => [
        'success' => 'Successful Request',
        'fail' => 'Fail Request'
    ],
    'login' => [
        'success' => 'Login Successfully',
        'fail' => 'Login Fail'
    ],
    'logout' => [
        'success' => 'Logout Successfully',
        'fail' => 'Logout Fail',
        'auth.sendEmail.fail'
    ],
    'send_email' => [
        'success' => 'send email Successfully',
        'fail' => 'send email Fail',
        'auth.reset_password.token_expired'
    ],
    'reset_password' => [
        'token_expired' => 'Token provided is expired.',
        'token_invalid' => 'Token provided is incorrect.',
        'forbidden' => 'This user was not allowed to reset password.',
        'send_email_success' => 'An email was sent to your email address, please check your email for next step.'
    ],
    'token' => [
        'valid' => 'Token valid',
        'invalid' => 'Token invalid'
    ],
    'profile' => [
        'get_profile_success' => '',
        'update_profile_success' => '',
        'update_avatar_success' => ''
    ]
];
