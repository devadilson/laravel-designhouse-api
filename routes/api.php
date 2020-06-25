<?php

// Public routes
Route::get('me', 'User\MeController@getMe');

// designs
Route::get('designs', 'Designs\DesignController@index');
Route::get('designs/{id}', 'Designs\DesignController@findDesign');
Route::get('designs/slug/{slug}', 'Designs\DesignController@findBySlug');

// designs
Route::get('designs', 'Designs\DesignController@index');

//users
Route::get('users', 'User\UserController@index');
Route::get('user/{username}', 'User\UserController@findByUsername');
Route::get('users/{id}/designs', 'Designs\DesignController@getForUser');

// Route group for authenticated users only
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::put('settings/profile', 'User\SettingsController@updateProfile');
    Route::put('settings/password', 'User\SettingsController@updatePassword');

    // Upload Designs
    Route::post('designs', 'Designs\UploadController@upload');
    Route::put('designs/{id}', 'Designs\DesignController@update');
    Route::get('designs/{id}/byUser', 'Designs\DesignController@userOwnsDesign');

    // Likes and Unlikes
    Route::post('designs/{id}/like', 'Designs\DesignController@like');
    Route::get('designs/{id}/liked', 'Designs\DesignController@checkIfUserHasLiked');

    // Comments
    Route::post('designs/{id}/comments', 'Designs\CommentController@store');
    Route::put('comments/{id}', 'Designs\CommentController@update');
    Route::delete('comments/{id}', 'Designs\CommentController@destroy');

    Route::delete('designs/{id}', 'Designs\DesignController@destroy');
});

// Routes for guests only
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'Auth\VerificationController@resend');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});
