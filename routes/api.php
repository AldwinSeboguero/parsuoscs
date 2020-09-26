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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('colleges', 'Admin\CollegeController');
Route::resource('programs', 'Admin\ProgramController');
Route::resource('sections', 'Admin\SectionController');
Route::resource('semesters', 'Admin\SemesterController');
Route::resource('purposes', 'Admin\PurposeController');
Route::resource('campuses', 'Admin\CampusController');
Route::resource('students', 'Admin\StudentController');

Route::post('login','UserController@login')->name('login');

