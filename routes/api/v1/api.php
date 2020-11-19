<?php

use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
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


 
 Route::group(['middleware' => ['auth'],'namespace'=>'Admin'], function() {
     //
 });
 
    Route::get('verify', 'Admin\UserController@verify');
    Route::resource('colleges', 'Admin\CollegeController');
    Route::resource('programs', 'Admin\ProgramController');
    Route::resource('sections', 'Admin\SectionController');
    Route::resource('semesters', 'Admin\SemesterController');
    Route::resource('purposes', 'Admin\PurposeController');
    Route::resource('campuses', 'Admin\CampusController');
    Route::resource('students', 'Admin\StudentController');
    Route::resource('users', 'Admin\UserController');
    Route::resource('roles', 'Admin\RoleController');
    Route::resource('clearancerequests', 'Admin\ClearanceRequestController');
    Route::resource('submittedclearances', 'Admin\SubmittedController');
    Route::resource('cashiers', 'Admin\CashierController');
    Route::post('users/delete', 'Admin\UserController@deleteAll');
    
    Route::post('email/verify', 'Admin\UserController@verifyEmail');
    
    Route::post('user/role','Admin\UserController@changeRole');
    
    Route::get('authuser','Admin\UserController@userInfo');
    
    
    
    
    Route::resource('completed', 'Admin\CompletedClearanceController');
 




Route::post('login','Admin\UserController@login')->name('login');

Route::prefix('/user')->group(function(){
        
        Route::post('/login','api\v1\LoginController@login');
        Route::middleware('auth:api')->get('/all','api\v1\user\UserController@index');
        Route::middleware('auth:api')->get('/current','api\v1\user\UserController@currentUser');
        
});

