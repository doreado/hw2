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

Route::get('/whoami', 'App\Http\Controllers\UsersController@whoami');
Route::get('/search_users/{user_query}', 'App\Http\Controllers\UsersController@searchUsers');
Route::get('/get_pics/{user_id}', 'App\Http\Controllers\UsersController@getPics');
Route::get('/get_username/{user_id}', 'App\Http\Controllers\UsersController@getUsername');
Route::get('/is_registered/{field}/{value}', 'App\Http\Controllers\UsersController@isRegistered');
Route::get('/is_followed/{other_id}', 'App\Http\Controllers\UsersController@isFollowed');
Route::get('/toggle_follow/{following_id?}/{to_follow}', 'App\Http\Controllers\UsersController@toggleFollow');
Route::post('/register', 'App\Http\Controllers\UsersController@addUser');

Route::get('/login', 'App\Http\Controllers\LoginController@show');
Route::post('/login/check_credential', 'App\Http\Controllers\LoginController@checkCredential');
Route::get('/home', 'App\Http\Controllers\HomeController@getHome');

Route::get('/search_movie/{movie}', 'App\Http\Controllers\MovieController@searchMovie');
Route::get('/get_movie_poster/{movie_id}', 'App\Http\Controllers\MovieController@getMoviePoster');
Route::get('/toggle_movie_in_watchlist/{movie_id}', 'App\Http\Controllers\MovieController@toggleMovieInWatchlist');

Route::get('/add_post/{type}/{type_id}/{text}', 'App\Http\Controllers\PostController@addPost');
Route::get('/get_posts/{offset}', 'App\Http\Controllers\PostController@getPosts');
Route::get('/is_liked/{post_id}', 'App\Http\Controllers\PostController@isLiked');
Route::get('/get_like_number/{post_id}', 'App\Http\Controllers\PostController@getLikeNumber');
Route::get('/add_like/{post_id}', 'App\Http\Controllers\PostController@addLike');
Route::get('/remove_like/{post_id}', 'App\Http\Controllers\PostController@removeLike');
