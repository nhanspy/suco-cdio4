<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->namespace('Api\V1')->group(function () {

    Route::prefix('projects')->group(function () {
        Route::middleware('device_id')->group(function () {
            Route::get('/', 'ProjectController@all');
            Route::get('/{projectId}', 'ProjectController@show');
            Route::get('/search', 'ProjectController@search');
            Route::get('/{projectId}/translations', 'ProjectController@getTranslations');
        });
    });

    Route::prefix('translations')->group(function () {
        Route::middleware('device_id')->group(function () {
            Route::get('/', 'TranslationController@all');
            Route::get('/{translationId}', 'TranslationController@show');
            Route::get('/top-search', 'TranslationController@topSearch');
            Route::post('/search', 'TranslationController@search');
            Route::post('/statistic', 'StatisticController@collectData');
        });
    });

    Route::prefix('comments')->group(function () {
        Route::middleware('device_id')->group(function () {
            Route::get('/', 'CommentController@all');
            Route::get('/{commentId}', 'CommentController@show');
        });
    });

    Route::prefix('push')->group(function () {
        Route::middleware(['device_id:required', 'jwt:api'])->group(function () {
            Route::post('/', 'PushController@create');
            Route::delete('/', 'PushController@delete');
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
            Route::get('/password/redirect/{token}/email/{email}',
                'AuthController@redirectResetPasswordLink'
            )->name('auth.password.redirect');
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

});
