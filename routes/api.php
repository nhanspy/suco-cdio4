<?php

use Illuminate\Support\Facades\Route;

Route::pattern('id', '[0-9]+');
Route::pattern('projectId', '[0-9]+');
Route::pattern('translationId', '[0-9]+');

/**
 * middleware: api - default middleware
 * middleware: jwt - logged in
 * middleware: jwt:api - only for user
 * middleware: jwt:admin - only for admin
 * middleware: device_id - required Device-Id in header
 */
require_once 'apiV1.php';

require_once 'apiWeb.php';
