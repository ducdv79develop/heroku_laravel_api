<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->namespace('Api\Auth')->group(function () {
    Route::post('login', 'LoginController@login');
    Route::post('register', 'RegisterController@register');
});

Route::prefix('admin')->middleware('auth:api')->namespace('Api\Admin')->group(function () {
    Route::get('list-member', 'AdminController@listMember');
    Route::post('show-member', 'AdminController@showMember');
});
