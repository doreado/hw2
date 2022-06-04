<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test/', function () {
    return 'testest';
});

Route::get('/signup', 'App\Http\Controllers\SignupController@show');
Route::post('/register', 'App\Http\Controllers\UsersController@addUser');
Route::get('/login', 'App\Http\Controllers\LoginController@show');
Route::post('/login/check_credential', 'App\Http\Controllers\LoginController@checkCredential');
Route::get('/is_registered/{field}/{value}', 'App\Http\Controllers\UsersController@isRegistered');
Route::get('/whoami', 'App\Http\Controllers\UsersController@whoami');
Route::get('/get_pics/{user_id}', 'App\Http\Controllers\UsersController@getPics');
Route::get('/home', 'App\Http\Controllers\HomeController@getHome');
Route::get('/search_movie/{movie}', 'App\Http\Controllers\MovieController@searchMovie');
Route::get('/add_post/{type}/{type_id}/{text}', 'App\Http\Controllers\PostController@addPost');
