<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/docs', function () {
    return view('api/v1');
});

Route::get('/admin/docs', function () {
    return view('api/admin');
});
