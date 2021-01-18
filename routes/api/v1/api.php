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
    Route::post('campusListener', 'Admin\StudentController@campusListener');
    Route::post('programListener', 'Admin\StudentController@programListener');

    Route::resource('users', 'Admin\UserController');
    Route::resource('roles', 'Admin\RoleController');
    Route::resource('clearancerequests', 'Admin\ClearanceRequestController');
    Route::post('approveclearancerequest', 'Admin\ClearanceRequestController@approveRequest');
    Route::resource('clearedclearancerequests', 'Admin\CompletedClearanceController');

    
    Route::resource('submittedclearances', 'Admin\SubmittedController');
    Route::resource('clearances', 'Admin\ClearanceController');
    Route::resource('deficiencies', 'Admin\DeficiencyController');

    //signatories route
    Route::resource('cashiers', 'Admin\CashierController');
    Route::resource('programdirectors', 'Admin\ProgramDirectorController');
    Route::resource('deans', 'Admin\DeanController');
    Route::resource('stcouncils', 'Admin\StCouncilController');
    Route::resource('registrars', 'Admin\RegistrarController');
    Route::resource('osass', 'Admin\OSASController');
    Route::resource('librarys', 'Admin\LibraryController');

    Route::post('users/delete', 'Admin\UserController@deleteAll');
    
    Route::post('email/verify', 'Admin\UserController@verifyEmail');
    
    Route::post('user/role','Admin\UserController@changeRole');
    
    Route::get('authuser','Admin\UserController@userInfo');
    
    
    
    
    Route::resource('completed', 'Admin\CompletedClearanceController');
    Route::resource('admindashboard', 'Admin\AdminController');
 




Route::post('login','Admin\UserController@login')->name('login');

Route::prefix('/user')->group(function(){
        
        Route::post('/login','api\v1\LoginController@login');
        Route::middleware('auth:api')->get('/all','api\v1\user\UserController@index');
        Route::middleware('auth:api')->get('/current','api\v1\user\UserController@currentUser');
        
});
Route::get('getUser', 'Admin\AdminController@getUser');



//secrets
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//STudents routes
Route::get('activeClearance', 'Admin\ActiveClearanceController@activeClearance');
Route::post('sendRequest', 'Admin\ActiveClearanceController@sendRequest');
Route::resource('purposesetup', 'Admin\StudentPurposeSetupController');
Route::post('activation/verify', 'Admin\ActivationController@verifyActivation');
Route::post('deficiencies/approve', 'Admin\DeficiencyController@approve');
Route::post('user/register','api\v1\LoginController@register');
Route::post('submitClearance', 'Admin\ActiveClearanceController@submitClearance');