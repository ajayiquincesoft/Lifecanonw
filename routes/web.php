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

Route::match(['get', 'post'], '/', 'FrontController@index')->name('welcome');
Route::post('contactform-email', 'FrontController@contactform')->name('contactform');
Route::get('contactMail', 'FrontController@contactMail')->name('contactMail');
Route::get('free-session', 'FrontController@firstFreeSession')->name('firstFreeSession');
Route::get('paid-session', 'FrontController@firstPaidSession')->name('firstPaidSession');
Route::get('refresh_captcha', 'FrontController@refreshCaptcha')->name('refresh_captcha');
Route::get('useraccount-delete', 'FrontController@useraccountdelete')->name('account_delete');
Route::post('useraccount-delete', 'FrontController@useraccountMessage')->name('account_delete');

/*Route::get('/card/{cardId}/{Id}','FrontController@SearchQrCode'); */

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => true, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);
// Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);



Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/verify-email/{id}', [App\Http\Controllers\ApiController::class, 'verifyEmail']);
Route::get('/reset_password/{id}', [App\Http\Controllers\ApiController::class, 'ResetPassword']);
Route::get('/password-updated', [App\Http\Controllers\ApiController::class, 'passwordUpdated'])->name('passwordupdated');
Route::post('/reset-password/{id}', [App\Http\Controllers\ApiController::class, 'postResetPassword'])->name('ResetPassword');
Route::match(['get','post'],'/contact', 'User\UserController@contactUs')->name('contact-us');
Route::get('/user-login', 'User\UserController@getLogin')->name('user-login');
Route::post('/user-login', 'User\UserController@postLogin')->name('user-login');

Route::get('/logout', 'User\UserController@logout')->name('user-logout');
Route::get('/user-signup', 'User\UserController@getSignUp')->name('user-signup');
Route::get('/client-signup', 'User\UserController@getClientsignUp')->name('client-signup');
Route::get('/coach-signup', 'User\UserController@getCoachsignUp')->name('coach-signup');
Route::post('/user-register', 'User\UserController@saveSignUp')->name('user-register');
Route::get('/{slug}', [App\Http\Controllers\FrontController::class, 'show']);
Route::namespace('Admin')->prefix('Admin')->middleware(['auth','admin'])->group(function () {

    Route::resource('user', 'UserController');
    Route::get('reported-user/{type}', 'UserController@viewReportedUser')->name('admin.viewReportedUser');
    //Route::get('user/{type}', 'UserController@viewClientUser')->name('admin.viewClientUser');
    Route::get('test-email', 'UserController@testEmail')->name('admin.testEmail');
    Route::match(['get', 'put'], 'profile', 'UserController@profile')->name('admin.profile');
    Route::match(['get', 'put'], 'change-password', 'UserController@updatePassword')->name('admin.password');
    Route::resource('page', 'PageController');
    Route::resource('feedback', 'FeedbackController');

    Route::resource('template', 'TemplateController');

    /*Route::resource('banner', 'BannerController');
    Route::resource('course', 'CourseController');
    Route::resource('cardtype', 'CardTypeController');
    Route::resource('trainer', 'TrainerController');
    Route::resource('certs', 'CertsController');
    Route::post('certs/copy/{id}', 'CertsController@Copy')->name('certs.copy');

    Route::match(['get','put'], 'profile','UserController@profile')->name('admin.profile');
    Route::match(['get','put'],'change-password','UserController@updatePassword')->name('admin.password');
    Route::match(['get','put'],'setting','UserController@setting')->name('admin.setting');
    Route::get('sms-logs','UserController@smsLogs')->name('admin.smsLogs');

    // card routes
    Route::resource('card', 'CardController');
    Route::get('card-export', 'CardController@CardExport')->name('card.export');

    Route::match(['get','post'], 'add-card-course/{id}','CardController@addCardCourse')->name('card.addCourse');
    Route::match(['get','post'], 'edit-card-course/{id}','CardController@editCardCourse')->name('card.editCourse');
    Route::delete('delete-card-course/{id}','CardController@deleteCardCourse')->name('card.deleteCourse');

    // card realtion meta data
    Route::match(['get','post'], 'add-card-type/{id}','CardController@addCardType')->name('card.addCardType');
    Route::match(['get','post'], 'edit-card-type/{id}','CardController@editCardType')->name('card.editCardType');
    Route::delete('delete-card-type/{id}','CardController@deleteCardType')->name('card.deleteCardType');

    // Route for view/blade file.
    Route::get('importExport', function (){
        return view('admin/card/import');
    });
    Route::post('importExcel', 'CardController@importExcel'); */
});

Route::namespace('User')->prefix('User')->middleware(['auth','user'])->group(function(){
    Route::get('/dashboard/{s?}', 'UserController@dashboard')->name('user.dashboard');
    Route::get('/view-profile', 'UserController@viewprofile')->name('user.viewprofile');
    Route::post('/save-profile', 'UserController@saveprofile')->name('user.saveprofile');
    Route::get('/reset-password', 'UserController@resetPassword')->name('user.resetpassword');
    Route::post('/save-reset-password', 'UserController@saveResetPassword')->name('user.saveresetpassword');
    Route::post('/add-coach', 'UserController@addCoach')->name('client.addcoach');
    Route::get('/view-coach/{coach_id}', 'UserController@viewCoach')->name('client.viewcoach');
    
    //coach route
    Route::get('/coach-plan', 'UserController@plan')->name('coach.plan');
    Route::get('/pay', 'UserController@pay')->name('pay');
    Route::post('/pay', 'UserController@postPay')->name('pay');
    Route::get('/add-client', 'UserController@addClient')->name('coach.addclient');
    Route::post('/add-client', 'UserController@saveaddClient')->name('coach.addclient');
    Route::get('/client-code-generate-by-coach', 'UserController@clientCodeGenerateByCoach')->name('coach.clientcodegeneratebycoach');
    Route::get('/view-added-client/{client_id}', 'UserController@ViewAddedClient')->name('coach.viewaddedclient');  

    Route::post('/coach-appointment', 'UserController@CouchAppointmentDate')->name('coachappointment.date');
    Route::post('/client-appointment', 'UserController@ClientAppointmentDate')->name('clientappointment.date');

    Route::post('update-goal', 'UserController@updategoalstatus')->name('coach.updategoalstatus');
    Route::get('delete-goal/{id}', 'UserController@deleteGoal')->name('coach.deletegoal');
    Route::post('addupdategoal', 'UserController@addupdategoal')->name('coach.addupdategoal');
    Route::get('getaddorupdatestripe', 'UserController@getAddOrUpdateStripe')->name('coach.getaddorupdatestripe');
    Route::post('saveaddorupdatestripe', 'UserController@saveaddorupdatestripe')->name('coach.saveaddorupdatestripe');
	Route::post('addnotes', 'UserController@addnotes')->name('coach.addnotes');
	Route::post('updatenote', 'UserController@updatenote')->name('coach.updatenote');
	Route::post('deletenote', 'UserController@deletenote')->name('coach.deletenote');
	Route::post('addhabits', 'UserController@addhabits')->name('coach.addhabits');
	Route::post('listhabits', 'UserController@listhabits')->name('coach.listhabits');
	Route::post('updatehabit', 'UserController@updatehabit')->name('coach.updatehabit');
	Route::post('deletehabit', 'UserController@deletehabit')->name('coach.deletehabit');
	Route::post('update_habit_item_status', 'UserController@UpdateHabitItemStatus')->name('UpdateHabitItemStatus');
	Route::post('update_habit_item_status_one', 'UserController@UpdateHabitItemStatusOne')->name('UpdateHabitItemStatusOne');
	Route::post('archive_added_clients', 'UserController@archiveAddedClients')->name('coach.archiveAddedClients');
	Route::post('gaolscore', 'UserController@gaolscore')->name('coach.gaolscore');
	Route::post('clientappointment', 'UserController@clientappointment')->name('coach.clientappointment');
	Route::get('get_availabletime', 'UserController@get_availabletime')->name('coach.get_availabletime');
	Route::get('mysubscription', 'UserController@mysubscription')->name('coach.mysubscription');
	Route::post('cancelsubscription', 'UserController@cancelsubscription')->name('coach.cancelsubscription');
	Route::get('getfeedback', 'UserController@Getfeedback')->name('coach.Getfeedback');
	Route::post('add_feedback', 'UserController@AddFeedback')->name('coach.AddFeedback');
	Route::get('earning', 'UserController@earning')->name('coach.earning');
	Route::get('get_coachavailability', 'UserController@getCoachAvailability')->name('coach.getCoachAvailability');
    Route::post('clientlisthabits', 'UserController@clientlisthabits')->name('client.listhabits');
	Route::post('availability', 'UserController@availability')->name('coach.availability');
	Route::post('earning', 'UserController@earning')->name('coach.earning');
	Route::get('get_notifications/{clientId}', 'UserController@getNotifications')->name('coach.getNotifications');
	Route::get('unreadnotifications', 'UserController@unreadnotifications')->name('coach.unreadnotifications');
	Route::post('readNotificationStatus', 'UserController@readNotificationStatus')->name('coach.readNotificationStatus');
	Route::get('get_reinstateclient/{clientId}', 'UserController@get_reinstateclient')->name('coach.get_reinstateclient');
	Route::post('reinstate_client', 'UserController@reinstateClient')->name('coach.reinstateClient');
	
	Route::post('addjournal', 'UserController@addjournal')->name('client.journal');
    Route::post('addappointment', 'UserController@addappointment')->name('client.addappointment');
    Route::post('get_availabletime', 'UserController@get_availabletime')->name('get_availabletime');
    Route::post('get_appointmentdetails', 'UserController@getappointmentdetails')->name('getappointmentdetails');

    Route::post('reschedule_appointment', 'UserController@reschedule_appointment')->name('reschedule_appointment'); 
	Route::post('fcm_token_save', 'UserController@fcmTokensave')->name('fcmTokensave');
	Route::post('coachaddappointment', 'UserController@coachaddappointment')->name('coachaddappointment');
	Route::get('deleteaddedclient/{id}', 'UserController@deleteaddedclient')->name('coach.deleteaddedclient');
    
    
});

// user route start


