<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TodoController;
use App\Http\Controllers\API\MajorController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\SchoolController;


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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'authenticate']);
Route::post('forget-password-email', [UserController::class, 'ForgetPasswordEmail']);
Route::post('check-forget-password-code', [UserController::class, 'checkForgetPasswordCodeVerification']);
Route::post('update-forget-password', [UserController::class, 'updateForgetPassword']);
Route::post('googleLogin', [UserController::class,'googleLogin'])->name('googleLogin');
route::get('invalid',[UserController::class,'invalid'])->name('invalid');
route::get('cache-clear',[UserController::class,'cacheClear'])->name('cache-clear');
// route::get('store',[UserController::class,'store'])->name('store');

Route::get('store', function () {

    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];

    \Mail::to('your_receiver_email@gmail.com')->send(new \App\Mail\OrderShipped($details));

    dd("Email is Sent.");
});



Route::middleware(['auth:api'])->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('profile','profile')->name('profile');
            Route::post('update-profile','UpdateProfile')->name('update-profile');
            Route::post('change-password','changePassword')->name('change-password');
            Route::get('notification-setting','notificationSetting')->name('notification-setting');
            Route::get('notification','notification')->name('notification');
            Route::post('notification-read','notificationRead')->name('notification-read');

        });

        Route::controller(TodoController::class)->group(function () {
            Route::post('add-todo','addTodo')->name('add-todo');
            Route::get('get-todo-list','getTodoList')->name('get-todo-list');
        });

        Route::controller(AdminController::class)->group(function () {
            Route::post('add-announcement','addAnnouncement')->name('add-announcement');
            Route::get('admin-notification-view','adminNotificationView')->name('admin-notification-view');
            Route::post('admin-notification-read','adminNotificationRead')->name('admin-notification-read');
            Route::get('student-list','studentList')->name('student-list');
            Route::post('account-verification','accountVerification')->name('account-verification');
            Route::post('account-delete','accountDelete')->name('account-delete');
            Route::get('admin-search-student','adminSearchStudent')->name('admin-search-student');
            Route::post('admin-update','adminUpdate')->name('admin-update');
            route::get('my-program-data','myProgramData')->name('my-program-data');
        });

        Route::controller(MajorController::class)->group(function () {
            Route::get('get-major','getMajor')->name('get-major');
            Route::get('get-concentration','getMoncentration')->name('get-concentration');
            Route::get('get-program','getProgram')->name('get-program');
            Route::post('saveDegreePlan','saveDegreePlan')->name('saveDegreePlan');

            Route::get('get-term','getTerm')->name('get-term');
            Route::get('get-course','getCourse')->name('get-course');
            Route::get('get-years','getYears')->name('get-years');
            Route::get('get-semester','getSemester')->name('get-semester');
            Route::post('save-course','saveCourse')->name('save-course');

            Route::get('my-term','myTerm')->name('my-term');
            Route::get('my-course','myCourse')->name('my-course');
            Route::get('get-subject','getSubject')->name('get-subject');
            Route::post('save-course-catalog','saveCourseCatalog')->name('save-course-catalog');
            Route::get('announcement','announcement')->name('announcement');


        });
        Route::controller(MessageController::class)->group(function () {
            Route::get('send-notification-to-support','sendNotificationToSupport')->name('send-notification-to-support');
            Route::get('notification_for_user','notification_for_user')->name('notification_for_user');
        });




});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(SchoolController::class)->group(function () {
            Route::get('get-all-school','getSchool')->name('get-all-school');
        });
