<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Web')->group(function () {
    Route::prefix('projects')->group(function () {
        Route::middleware('device_id')->group(function () {
            Route::get('/', 'ProjectController@all');
            Route::get('/{projectId}', 'ProjectController@show');
            Route::get('/search', 'ProjectController@search');
            Route::get('/{projectId}/translations', 'ProjectController@getTranslations');
            Route::get('/count', 'ProjectController@count');
            Route::get('/no-paginate', 'ProjectController@getAllNoPaginate');
        });

        Route::middleware('jwt:admin')->group(function () {
            Route::post('/', 'ProjectController@create');
            Route::put('/{projectId}', 'ProjectController@update');
            Route::put('/{projectId}/restore', 'ProjectController@restore');
            Route::delete('/{projectId}', 'ProjectController@delete');
        });
    });

    Route::prefix('translations')->group(function () {
        Route::middleware('device_id')->group(function () {
            Route::get('/', 'TranslationController@all');
            Route::get('/{translationId}', 'TranslationController@show');
            Route::get('/top-search', 'TranslationController@topSearch');
            Route::get('/top-like', 'TranslationController@topLike');
            Route::get('/top-comment', 'TranslationController@topComment');
            Route::get('/recent-search', 'TranslationController@recentSearch');
            Route::get('{translationId}/comments', 'TranslationController@getComment');
            Route::post('/search', 'TranslationController@search');
            Route::post('/statistic', 'StatisticController@collectData');
        });

        Route::middleware('jwt:admin')->group(function () {
            Route::post('/', 'TranslationController@create');
            Route::put('/{translationId}', 'TranslationController@update');
            Route::delete('/{translationId}', 'TranslationController@delete');

            Route::prefix('/elastic')->group(function () {
                Route::post('/index', 'TranslationController@elasticIndex');
                Route::post('/add-all-to-index', 'TranslationController@addToIndex');
            });

            Route::post('/export', 'TranslationController@exportExcel');
            Route::post('/import', 'TranslationController@importExcel');
        });
    });

    Route::prefix('comments')->group(function () {
        Route::middleware('device_id')->group(function () {
            Route::get('/', 'CommentController@all');
            Route::get('/{commentId}', 'CommentController@show');
        });

        Route::middleware('jwt:api')->group(function () {
            Route::post('/', 'CommentController@create');
            Route::put('/{commentId}', 'CommentController@update');
            Route::delete('/{commentId}', 'CommentController@delete');
        });
    });

    Route::prefix('statistic')->group(function () {
        Route::middleware('device_id')->group(function () {
            Route::get('/', 'StatisticController@statistic');
        });
    });

    Route::prefix('auth')->group(function () {
        Route::middleware(['formThrottle', 'device_id:required'])->group(function () {
            Route::post('/login', 'AuthController@login');
            Route::post('/login/with-work-chat', 'AuthController@loginWithWorkChat');
        });

        Route::middleware('formThrottle')->group(function () {
            Route::post('/logout', 'AuthController@logout');
            Route::post('/register', 'AuthController@register');
            Route::put('/password/reset', 'AuthController@resetPassword');
            Route::post('/password/reset/send-email', 'AuthController@sendResetPasswordEmail');
            Route::get('/device-id', 'AuthController@responseDeviceId');
        });

        Route::middleware('jwt:api')->group(function () {
            Route::get('/profile', 'AuthController@profile');
            Route::put('/profile', 'AuthController@updateProfile');
            Route::post('/profile/avatar', 'AuthController@updateAvatar');
            Route::put('/password/change', 'AuthController@changePassword');
            Route::post('/token/validate', 'AuthController@validateToken');
        });

        Route::middleware('device_id:required')->group(function () {
            Route::get('/translations/archived', 'AuthController@archives');
            Route::get('/translations/archived/count', 'AuthController@countArchives');
            Route::post('/translation/{id}/like', 'AuthController@like');
            Route::delete('/translation/{id}/like', 'AuthController@unLike');
            Route::post('/translation/{id}/archive', 'AuthController@archive');
            Route::delete('/translation/{id}/archive', 'AuthController@unArchive');
        });
    });

    Route::prefix('users')->group(function () {
        Route::middleware('jwt:admin')->group(function () {
            Route::get('/', 'UserController@all');
            Route::get('/count', 'UserController@count');
            Route::get('/{id}', 'UserController@show');
            Route::post('/', 'UserController@store');
            Route::put('/{id}', 'UserController@update');
            Route::post('/{id}/avatar', 'UserController@updateAvatar');
            Route::delete('/{id}', 'UserController@delete');
            Route::put('/{id}/restore', 'UserController@restore');
            Route::get('/names', 'UserController@getNames');
            Route::get('/emails', 'UserController@getEmails');
            Route::get('/filter', 'UserController@filter');
            Route::get('/{id}/archives', 'UserController@archives');
            Route::get('/{id}/likes', 'UserController@likes');
            Route::get('/{id}/histories', 'UserController@histories');
        });
    });

    Route::prefix('admin')->group(function () {
        Route::middleware('formThrottle')->group(function () {
            Route::post('/login', 'AdminController@login');
            Route::post('/logout', 'AdminController@logout');
            Route::put('/password/reset', 'AdminController@resetPassword');
            Route::post('/password/reset/send-email', 'AdminController@sendResetPasswordEmail');
        });

        Route::middleware('jwt:admin')->group(function () {
            Route::get('/profile', 'AdminController@profile');
            Route::put('/profile', 'AdminController@updateProfile');
            Route::post('/profile/avatar', 'AdminController@updateAvatar');
            Route::post('/token/validate', 'AdminController@validateToken');
            Route::put('/password/change', 'AuthController@changePassword');
        });
    });

    Route::prefix('notifications')->group(function () {
        Route::middleware('jwt:admin')->group(function () {
            Route::get('/', 'NotificationController@all');
            Route::get('/count', 'NotificationController@count');
            Route::get('/{id}', 'NotificationController@show');
            Route::post('/', 'NotificationController@store');
            Route::put('/{id}', 'NotificationController@update');
            Route::delete('/{id}', 'NotificationController@delete');
            Route::put('/{id}/restore', 'NotificationController@restore');
            Route::get('/{id}/push', 'NotificationController@push');
            Route::post('/push', 'NotificationController@createAndPush');
            Route::put('/{id}/push', 'NotificationController@updateAndPush');
            Route::put('/{id}/project/{projectId}', 'NotificationController@attachProject');
            Route::delete('/{id}/project/{projectId}', 'NotificationController@detachProject');
        });
    });

    Route::prefix('push')->group(function () {
        Route::middleware(['device_id:required', 'jwt:api'])->group(function () {
            Route::post('/', 'PushController@create');
        });
    });
});
