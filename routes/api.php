<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;

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

Route::post('login', [ApiController::class, 'login']);
Route::post('register', [ApiController::class, 'register']);
Route::post('resend', [ApiController::class, 'resend']);
Route::post('forgot-password', [ApiController::class, 'forgotPassword']);
Route::get('get_plans', [ApiController::class, 'plans']);
route::get('pages', [ApiController::class, 'pages']);

Route::group(['middleware' => ['jwt.verify']], function () {
	Route::get('logout', [ApiController::class, 'logout']);
	Route::post('update_password', [ApiController::class, 'updatePassword']);
	Route::post('get_user', [ApiController::class, 'get_user']);
	Route::post('get_ios_user', [ApiController::class, 'get_ios_user']);
	Route::post('update_profile', [ApiController::class, 'updateProfile']);
	Route::post('availability', [ApiController::class, 'availability']);
	Route::post('get_coach_availability', [ApiController::class, 'getCoachAvailability']);
	Route::post('get_availability', [ApiController::class, 'getDaysAvailability']);
	Route::post('get_availabletime', [ApiController::class, 'getAvalableTime']);
	Route::post('pay', [ApiController::class, 'makePayment']);
	Route::post('ios-pay', [ApiController::class, 'makePaymentForIos']);
	Route::post('restore-ios-pay', [ApiController::class, 'restorePaymentForIos']);
	Route::post('update-added-client', [ApiController::class, 'updateAddedClient']);
	Route::post('reinstate_client', [ApiController::class, 'reInstateClient']);
	Route::get('get_added_clients', [ApiController::class, 'getAddedClients']);
	Route::delete('delete_added_clients', [ApiController::class, 'deleteAddedClients']);
	Route::put('archive_added_clients', [ApiController::class, 'archiveAddedClients']);
	Route::post('add_coach', [ApiController::class, 'AddCoach']);
	Route::get('client_coach_list', [ApiController::class, 'clientCoachList']);
	Route::post('add_habits', [ApiController::class, 'AddHabits']);
	Route::post('update_habits', [ApiController::class, 'UpdateHabits']);
	Route::post('get_habits', [ApiController::class, 'GetHabits']);
	Route::post('delete_habit', [ApiController::class, 'DeleteHabit']);
	Route::post('update_habit_item_status', [ApiController::class, 'UpdateHabitItemStatus']);
	Route::post('update_habit_status', [ApiController::class, 'UpdateHabitStatus']);
	Route::post('add_journals', [ApiController::class, 'AddJournals']);
	Route::post('get_journals', [ApiController::class, 'getJournals']);
	Route::post('add_reminder', [ApiController::class, 'AddReminder']);
	Route::get('get_reminder', [ApiController::class, 'GetReminder']);
	Route::post('update_reminder', [ApiController::class, 'UpdateReminder']);
	Route::post('delete_reminder', [ApiController::class, 'DeleteReminder']);
	Route::post('add_note', [ApiController::class, 'AddNote']);
	Route::get('get_note', [ApiController::class, 'GetNote']);
	Route::post('update_note', [ApiController::class, 'UpdateNote']);
	Route::post('delete_note', [ApiController::class, 'DeleteNote']);
	Route::post('add_appointment', [ApiController::class, 'addAppointment']);
	Route::post('add_appointment_by_coach', [ApiController::class, 'addAppointmentByCoach']);
	Route::post('reschedule_appointment', [ApiController::class, 'rescheduleAppointment']);
	Route::get('get_appointment', [ApiController::class, 'getAppointment']);
	Route::get('get_appointmenttest', [ApiController::class, 'getAppointmenttest']);
	Route::get('get_appointment_by_coach', [ApiController::class, 'getAppointmentByCoach']);
	Route::get('get_appointment_detail', [ApiController::class, 'getAppointmentDetail']);
	Route::get('get_past_appointment', [ApiController::class, 'getPastAppointment']);
	Route::post('chat_room', [ApiController::class, 'chatRoom']);
	Route::post('add_or_update_stripe', [ApiController::class, 'addOrUpdateStripe']);
	Route::post('get_stripe', [ApiController::class, 'getStripe']);
	Route::post('send_code_for_stripe', [ApiController::class, 'sendCodeForStripe']);
	Route::post('verify_stripe', [ApiController::class, 'verifyStripe']);
	Route::post('list-subscription', [ApiController::class, 'listSubscription']);
	Route::post('cancel-subscription', [ApiController::class, 'cancelSubscription']);
	Route::post('earning', [ApiController::class, 'earning']);
	Route::post('save-fcm-token', [ApiController::class, 'savefcmToken']);
	Route::post('save-notifications', [ApiController::class, 'saveNotifications']);
	Route::post('read-notifications', [ApiController::class, 'readNotificationStatus']);
	Route::post('delete-notifications', [ApiController::class, 'deleteNotification']);
	Route::get('get-unread-notifications', [ApiController::class, 'getUnreadNotifications']);
	Route::get('get-all-notifications', [ApiController::class, 'getAllNotifications']);
	Route::get('get-goal', [ApiController::class, 'getGoal']);
	Route::post('add-goal', [ApiController::class, 'addGoal']);
	Route::post('update-goal', [ApiController::class, 'updateGoal']);
	Route::delete('delete-goal', [ApiController::class, 'deleteGoal']);
	Route::post('add_feedback', [ApiController::class, 'AddFeedback']);
	Route::get('get_feedback', [ApiController::class, 'GetFeedback']);
});
Route::get('get_all_feedback', [ApiController::class, 'GetAllFeedback']);
Route::get('send-notifications', [ApiController::class, 'sendNotificationForAllUser']);
Route::get('test-email', [ApiController::class, 'testEmail']);

