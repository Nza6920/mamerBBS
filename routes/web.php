<?php

// 主页路由
Route::get('/', 'TopicsController@index')->name('root');

// 用户身份验证相关的路由
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 用户注册相关路由
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 密码重置相关路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email 认证相关路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// 用户路由
Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);

// 话题路由
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('topics/{topic}/repliers', 'TopicsController@repliers')->name('topics.repliers');
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

// 帖子上传图片
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

// 话题分类路由
Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

// 话题回复
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

// 通知
Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);

// 无权限提醒路由
Route::get('permission-denied', 'PagesController@permissionDenied')->name('permission-denied');
