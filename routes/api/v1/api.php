<?php

use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\SemesterController;
use app\Http\Controllers\CampusController;
use app\Http\Controllers\ProgramController;
// use app\Http\Controllers\PurposeController;
use app\Http\Controllers\DesignationController;

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
    Route::resource('adminsemesters', 'Admin\SemesterController');
    Route::resource('graduations', 'Admin\GraduationController');
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
    Route::resource('registrarDeficiencyList', 'Admin\RegistrarDeficiencyController');
    //signatories route
    Route::resource('cashiers', 'Admin\CashierController');
    Route::resource('programdirectors', 'Admin\ProgramDirectorController');
    Route::resource('deans', 'Admin\DeanController');
    Route::resource('stcouncils', 'Admin\StCouncilController');
    Route::resource('registrars', 'Admin\RegistrarController');
    Route::resource('osass', 'Admin\OSASController');
    Route::resource('librarys', 'Admin\LibraryController');
    Route::resource('staffs', 'Admin\StaffController');

    Route::post('users/delete', 'Admin\UserController@deleteAll');
    
    Route::post('email/verify', 'Admin\UserController@verifyEmail');
    
    Route::post('user/role','Admin\UserController@changeRole');
    
    Route::get('authuser','Admin\UserController@userInfo');
    
    
    
    
    Route::resource('completed', 'Admin\CompletedClearanceController');
    Route::resource('admindashboard', 'Admin\AdminController');
 




// Route::post('login','Admin\UserController@login')->name('login');

Route::prefix('/user')->group(function(){
        
        Route::post('/login','api\v1\LoginController@login');
        Route::middleware('auth:api')->get('/all','api\v1\user\UserController@index');
        Route::middleware('auth:api')->get('/current','api\v1\user\UserController@currentUser');
        
});
Route::get('getUser', 'Admin\AdminController@getUser');



//secrets
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//STudents routes
Route::get('activeClearance', 'Admin\ActiveClearanceController@activeClearance');
Route::post('sendRequest', 'Admin\ActiveClearanceController@sendRequest');
Route::resource('purposesetup', 'Admin\StudentPurposeSetupController');
Route::post('activation/verify', 'Admin\ActivationController@verifyActivation');
Route::post('deficiencies/approve', 'Admin\DeficiencyController@approve');
Route::post('user/register','api\v1\LoginController@register');
Route::post('submitClearance', 'Admin\ActiveClearanceController@submitClearance');


//imports

Route::post('/students/import','Admin\StudentController@import');
Route::resource('siasaccounts', 'SIASAccountController');
Route::post('/siasaccounts/import','SIASAccountController@import');
Route::get('mysiasaccounts','SIASAccountController@getAccount');


Route::resource('clearancerequestscas', 'Admin\CASClearanceController');
Route::post('approveclearancerequestcas', 'Admin\CASClearanceController@approveRequest');

Route::resource('clearancerequestscbm', 'Admin\CBMClearanceController');
Route::post('approveclearancerequestcbm', 'Admin\CBMClearanceController@approveRequest');

Route::resource('clearancerequestscet', 'Admin\CETClearanceController');
Route::post('approveclearancerequestcet', 'Admin\CETClearanceController@approveRequest');

Route::resource('clearancerequestscoed', 'Admin\COEDClearanceController');
Route::post('approveclearancerequestcoed', 'Admin\COEDClearanceController@approveRequest');

Route::resource('clearancerequestssgs', 'Admin\SGSClearanceController');
Route::post('approveclearancerequestsgs', 'Admin\SGSClearanceController@approveRequest');




    
        // Send reset password mail
        Route::post('reset-password', 'Admin\AuthController@sendPasswordResetLink');
        
        // handle reset password form process
        Route::post('reset/password', 'Admin\AuthController@callResetPassword');
        
            Route::post('create', 'PasswordResetController@create');
            Route::post('find', 'PasswordResetController@find');
            Route::post('reset', 'PasswordResetController@reset');
            Route::post('changePassword', 'Admin\UserController@changePassword');
            Route::post('emailChangeCreate', 'EmailChangeController@create');
            Route::post('emailChangeFind', 'EmailChangeController@find');
            Route::post('emailChangeReset', 'EmailChangeController@reset');

            Route::post('dean/update','Admin\DeanController@changeDean');
            Route::post('pd/update','Admin\ProgramDirectorController@changePD');
            Route::get('pdf-create','PdfController@create');
            Route::get('pdf-createSGS','PdfController@createSGS');
            Route::get('sgs/pdf-create','PdfController@createSGS');
            Route::get('lhs/pdf-create','PdfController@createLHS');
            
            Route::post('staff/update','Admin\StaffController@changeStaff');
// Route::post('sociallogin/{provider}', 'Auth\AuthController@SocialSignup');
// Route::get('/auth/google/callback', 'OutController@index');

Route::get('auth/google', 'Auth\GoogleAuthController@redirectToGoogle');
Route::get('auth/google/callback','Auth\GoogleAuthController@handleGoogleCallback');
 
Route::post('copyPreviousStaff','Admin\StaffController@copyPreviousStaff');


Route::get('/students/activationcode/export', 'Admin\StudentController@export');
Route::post('/getFileName', 'Admin\StudentController@filename');
Route::post('/resetP', 'Admin\StudentController@resetP');

//v3 routes
Route::get('/active-clearance', 'V2\Student\ActiveClearanceController@index');
Route::post('/active-clearance/send-request', 'V2\Student\ActiveClearanceController@sendRequest');
Route::get('/active-clearance/pdf', 'V2\Student\ActiveClearanceController@createPdf');
Route::get('/active-clearance/signatory/pdf', 'V2\Student\ActiveClearanceController@signatoryCreatePdf');

Route::post('/active-clearance/submit', 'V2\Student\ActiveClearanceController@submitClearance');
Route::get('/active-clearance/clearances', 'V2\Student\ClearanceListController@index');

//Signatory Routes V3
Route::get('/clearance-requests', 'V2\Signatory\ClearanceRequestController@index');
Route::post('/clearance-requests/approve', 'V2\Signatory\ClearanceRequestController@approve');
Route::get('/clearance-requests/disapprove', 'V2\Signatory\ClearanceRequestController@disapprove');


//deficency
Route::post('/deficeincy/edit', 'Admin\DeficiencyController@update');


//Admin Route
Route::get('/getStaff', 'Admin\StaffController@getStaff');
Route::get('/getSemesters', 'Admin\SemesterController@getSemesters');




Route::resources([
    'semesters' => 'SemesterController',
    'campuses' => 'CampusController',
    'programs' => 'ProgramController',
    'designations' => 'DesignationController',
    'purposes' => 'Admin\PurposeController',

    'signatories' => 'Admin\StaffController',

]);

Route::get('/getprev', 'SemesterController@prev');
Route::get('/getnext', 'SemesterController@next');
Route::get('/getPrevDeanEnrollment', 'Admin\StaffController@getPrevDeanEnrollment');
Route::get('/getPrevCashierEnrollment', 'Admin\StaffController@getPrevCashierEnrollment');
Route::get('/getPrevLibraryEnrollment', 'Admin\StaffController@getPrevLibraryEnrollment');
Route::get('/getPrevOSASEnrollment', 'Admin\StaffController@getPrevOSASEnrollment');
Route::get('/getPrevGraduation', 'Admin\StaffController@getPrevGraduation');
Route::get('/getPrevRequestCredentials', 'Admin\StaffController@getPrevRequestCredentials');


Route::get('/copyPrevDeanEnrollment', 'Admin\StaffController@copyPrevDeanEnrollment');
Route::get('/copyPrevCashierEnrollment', 'Admin\StaffController@copyPrevCashierEnrollment');
Route::get('/copyPrevLibraryEnrollment', 'Admin\StaffController@copyPrevLibraryEnrollment');
Route::get('/copyPrevOSASEnrollment', 'Admin\StaffController@copyPrevOSASEnrollment');
Route::get('/copyPrevGraduation', 'Admin\StaffController@copyPrevGraduation');
Route::get('/copyPrevRequestCredentials', 'Admin\StaffController@copyPrevRequestCredentials');











