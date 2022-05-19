<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/{any}', function(){
//     return redirect('http://oscs.parsu.edu.ph');
// })->where('any','.*');
// Route::post('sociallogin/{provider}', 'Auth\AuthController@SocialSignup');
// Route::get('/auth/google/callback', 'OutController@index');
