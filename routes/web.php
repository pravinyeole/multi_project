<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\MapUserController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\PaperCostController;
use App\Http\Controllers\facultiesController;
use App\Http\Controllers\TeacherPaperController;
use App\Http\Controllers\RequestPinController;
use App\Http\Controllers\NormalUserController;
use App\Http\Controllers\PinCenterController;
use App\Http\Controllers\SuperAdminController;

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
header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );

Route::get('/artisancache', function()
{
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
});
// Main Page Route
Route::group(['middleware' => ['httpsProtocol']], function () {
    Route::get('/', [LoginController::class, 'showLoginForm']);
    Route::get('register/{mobile_num}/{invitation_id}', [RegisterController::class,'showRegistrationForm']);
    Route::get('register', [RegisterController::class,'showRegistrationForm']);
    Route::get('show-enter-otp/{user_id}/{mobileNumber}', [RegisterController::class, 'showEnterOtp'])->name('show-enter-otp');

    Route::post('login', [LoginController::class,'login'])->name('login');
    Route::post('register_user', [RegisterController::class,'register'])->name('register_user');
    Route::get('/user-invitation/{userInvitation}',[MapUserController::class, 'show']);

    Route::get('/home',[DashboardController::class,'dashboard'])->name('home');
   
    Auth::routes(['verify' => true]);
    Route::get('logout', [LoginController::class, 'logout']);

    // org selection screens

     /* Route Common */
    Route::group(['prefix' => 'common'], function () {
        Route::post('/delete',[CommonController::class, 'deleteRecord']);
        Route::post('/status',[CommonController::class, 'updateStatus']);
        Route::post('/users/update-status', [CommonController::class, 'updateStatus'])->name('users.update-status');
        Route::post('/send-otp', [CommonController::class, 'sendOTP'])->name('send-otp');
        Route::post('/resend-otp', [CommonController::class, 'resendOTP'])->name('resend-otp');

    });
    /* Route Common */
    
    Route::group(['prefix' => 'two-fact-auth'], function () {
        // Route::get('/', 			[UserController::class, 'index']);
        Route::get('updateProfile', [TwoFactorController::class, 'updateProfile']);
        Route::post('updateProfileAction', [TwoFactorController::class, 'updateProfileAction']);
        Route::get('twoFactor', [TwoFactorController::class, 'twoFactor']);
        Route::post('verifyOtp', [TwoFactorController::class, 'verifyOtp']);
        Route::get('resend', [TwoFactorController::class, 'resend']);
        Route::post('/profile/update', [TwoFactorController::class, 'updateProfileAction'])->name('profile.update');

    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 			    [UserController::class, 'index']);
        Route::get('create', 		    [UserController::class, 'create']);
        Route::get('profile', 		    [UserController::class, 'profile']);
        Route::post('change-password',  [UserController::class, 'change_password']);
        Route::post('update-profile',   [UserController::class, 'update_profile']);
        Route::post('save', 		    [UserController::class, 'store']);
        Route::get('edit/{id}', 	    [UserController::class, 'edit']);
        Route::get('view/{id}', 	    [UserController::class, 'view']);
        Route::post('update', 		    [UserController::class, 'update']);
        Route::post('delete', 		    [UserController::class, 'destroy']);
        Route::post('status', 		    [UserController::class, 'updateStatus']);
        Route::post('getTeamByUser', 	[UserController::class, 'getTeamByUser']);
        Route::get('loginUser/{user_id}', 	     [UserController::class, 'loginUser']);
        Route::get('get-return-login/{user_id}', [UserController::class, 'getReturnLogin']);
        Route::post('geTeamsbyInsuranceAgency',  [UserController::class, 'geTeamsbyInsuranceAgency']);
        Route::post('updateProfileAction',       [UserController::class, 'updateProfileAction']);
        Route::post('checkOTP',         [UserController::class, 'checkOTPexist']);
        Route::post('resendOTP',        [UserController::class, 'resendOTP']);
        Route::get('/map-user',         [MapUserController::class, 'index']);
        Route::post('checkEmail',       [UserController::class, 'checkEmail']);
    });

    Route::group(['prefix' => 'pin_center'], function () {
        Route::get('/', 			    [PinCenterController::class, 'index']);
        Route::get('/edit/{id}', 			    [PinCenterController::class, 'edit'])->name('pin_center.edit');
        Route::post('/update/{id}', 			    [PinCenterController::class, 'update']);
       
    });
    
    Route::group(['prefix' => 'superadmin'], function () {
        Route::get('/', 			    [SuperAdminController::class, 'index']);
        Route::get('/admin', 			    [SuperAdminController::class, 'admins']);
        Route::get('/admin_create_form', 			    [SuperAdminController::class, 'showAdminCreateFrom']);
        Route::post('/admin/save', 			    [SuperAdminController::class, 'saveAdmin']);
        Route::get('/admin/edit/{id}', 			    [SuperAdminController::class, 'showEditAdminFrom']);
        Route::post('/admin/update', 			    [SuperAdminController::class, 'updateAdmin']);
        // users list who dont have ref code but wan to join 
        Route::get('/users', 			    [SuperAdminController::class, 'userWithOutRefral']);
        //
        Route::get('/allusers', 			    [SuperAdminController::class, 'showAllUser']);
        //revoke super admin can revoke pin form any user in the system
        Route::get('/revokepin', 			    [SuperAdminController::class, 'showRevokePin']);
        Route::get('/user/details/{id}', 			    [SuperAdminController::class, 'getRevokePinUserDetails']);
        Route::post('/save_revoke', 			    [SuperAdminController::class, 'saveRevoke']);

        Route::get('/assignuser', 			    [SuperAdminController::class, 'showAssignUserFrom']);
        Route::post('/save-assigne-user', [SuperAdminController::class, 'saveAssignUsers'])->name('superadmin.save-assigne-user');
        Route::get('/announcement', [SuperAdminController::class, 'showannouncement']);
        Route::any('/announce_create', [SuperAdminController::class, 'announce_create']);
        Route::any('/delete/{id}', [SuperAdminController::class, 'delete']);
        

    });

    Route::group(['prefix' => 'normal_user'], function () {
        Route::get('/', 			    [NormalUserController::class, 'index']);
        Route::post('/create_id', [NormalUserController::class,'createId'])->name('normal_user.create_id');
        Route::get('/view/{id}', 			    [NormalUserController::class, 'view']);
        Route::get('/send_help', [NormalUserController::class, 'getSendHelpData'])->name('send_help');
        Route::get('/get_help', [NormalUserController::class, 'getGetHelpData'])->name('get_help');
        Route::get('/show-send-help-form/{id}/{mobileId}', 			    [NormalUserController::class, 'showSendHelpFrom']);
        Route::get('/show-get-help-form/{id}/{mobileId}', 			    [NormalUserController::class, 'showGetHelpFrom']);
        Route::post('save-send-help', [NormalUserController::class, 'saveSendHelp'])->name('normal_user.save_sh');
        // save-get-help-form
        Route::post('save-get-help-form', [NormalUserController::class, 'saveGetHelp'])->name('normal_user.save_gh');

        Route::get('paymentrequest', [NormalUserController::class, 'paymentrequest']);
        Route::get('payment_accept/{id}/{mobileId}', [NormalUserController::class, 'payment_accept']);


    });
    //for admin to accepet pins request
    Route::group(['prefix' => 'pins-request'], function () {
        Route::get('/', 			    [RequestPinController::class, 'showAdminRequestAcceptPage'])->name('pins-request.admin');
        Route::get('/edit/{pin_request_id}', [RequestPinController::class,'editPinRequestToAdminPage'])->name('request-pin.edit-request');
        Route::post('/update-request', [RequestPinController::class,'updatePinRequestToAdmin'])->name('request-pin.update-request');
    });

    //for normal user send rquest to admin
    Route::group(['prefix' => 'request-pin'], function () {
        Route::get('/', 			    [RequestPinController::class, 'index']);
        Route::post('/send-request', [RequestPinController::class,'sendPinRequestToAdmin'])->name('request-pin.send-request');
        Route::get('/direct_ref_user_list', [RequestPinController::class,'direct_ref_user_list'])->name('direct_ref_user_list');
    });
    Route::group(['prefix' => 'transferpin'], function () {
        Route::get('/', 			    [RequestPinController::class, 'adminTransferPin']);
        Route::post('/transsubmit', 			    [RequestPinController::class, 'adminTransferPinSubmit']);
    });

});
?>