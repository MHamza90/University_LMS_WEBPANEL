<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DegreeProgramController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ConcentrationController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\SchoolController;



use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\FrontController;
use App\Http\Controllers\Auth\VerificationController;


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

        // Route::any('/', function () {
        //     return view('welcome');
        // });

        Route::get('forgot-password',[FrontController::class,'forgotPasswords'])->name('forgot-password');
        Route::post('forgotPassword',[FrontController::class,'forgotPassword'])->name('forgotPassword');
        Route::post('updatePassword',[FrontController::class,'updatePassword'])->name('updatePassword');
        Route::get('resetpassword/{id}',[FrontController::class,'resetpassword'])->name('resetpassword');


        Auth::routes(['verify' => true]);
        route::get('/',[AdminController::class,'user_login'])->name('user-login');

        route::post('loginAdminProcess',[AdminController::class,'loginAdminProcess'])->name('loginAdminProcess');

        route::post('AdminRegisterPrcess',[AdminController::class,'AdminRegisterPrcess'])->name('AdminRegisterPrcess');

        route::get('user-register',[AdminController::class,'register'])->name('user-register');
        Route::get('CheckEmailVerify/{token}/{email}', [AdminController::class,'CheckEmailVerify'])->name('CheckEmailVerify');
        Route::get('verrify-email/{token}', [AdminController::class,'verrifyEmail'])->name('verrify-email');


    // Route::middleware([AdminMiddleware::class])->group(function(){


    Route::group(['middleware' => ['auth']], function() {

        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);

        Route::get('role-change-status', [RoleController::class, 'roleChangeStatus'])->name('role-change-status');
        Route::resource('degree-program', DegreeProgramController::class);

        Route::resource('announcement', AnnouncementController::class);
        Route::get('announcement-status',[AnnouncementController::class,'announcement_status'])->name('announcement-status');

        Route::resource('major',MajorController::class);
        Route::get('major-status',[MajorController::class,'major_status'])->name('major-status');

        Route::resource('program',ProgramController::class);
        Route::get('program-status',[ProgramController::class,'program_status'])->name('program-status');

        Route::resource('term',TermController::class);
        Route::get('term-status',[TermController::class,'term_status'])->name('term-status');
        // Route::get('get-program',[TermController::class,'getProgram'])->name('get-program');



        Route::resource('concentration',ConcentrationController::class);
        Route::get('concentration-status',[ConcentrationController::class,'concentration_status'])->name('concentration-status');
        Route::resource('school',SchoolController::class);
        Route::get('school-status',[SchoolController::class,'school_status'])->name('school-status');

        Route::resource('course',CourseController::class);
        Route::get('course-status',[CourseController::class,'course_status'])->name('course-status');
        Route::get('get-term',[CourseController::class,'getTerm'])->name('get-term');
        Route::get('get-program',[CourseController::class,'getProgram'])->name('get-program');
        Route::get('get-concentration',[CourseController::class,'getConcentration'])->name('get-concentration');
        Route::get('get-semster',[CourseController::class,'getSemster'])->name('get-semster');
        Route::get('get-semesters',[CourseController::class,'getSemesters'])->name('get-semesters');



        Route::controller(UserController::class)->group(function () {
            Route::get('view-manager', 'viewManager')->name('view-manager');
            Route::get('view-employee', 'viewEmployee')->name('view-employee');
            Route::get('employee-detail/{id}','EmployeeDetail')->name('employee-detail');
            Route::get('manager-detail/{id}','managerDetail')->name('manager-detail');


        });



        Route::controller(AdminController::class)->group(function () {
            Route::get('getUsers', 'getUsers')->name('getUsers');
            Route::post('addUpdateUser', 'addUpdateUser')->name('addUpdateUser');
            Route::post('update-profile-process', 'updateProfileProcess')->name('update-profile-process');
            Route::post('change-password','changePassword')->name('change-password');
            Route::get('delete-user', 'delete_user')->name('delete-user');
            Route::get('get-users', 'get_users')->name('get-users');
            Route::get('change-status', 'change_status')->name('change-status');
            Route::get('view-user', 'view_user')->name('view-user');
            Route::get('logouts', 'logouts')->name('logouts');
            Route::get('dashboard', 'dashboard')->name('dashboard');
            Route::get('profile', 'profile')->name('profile');
            Route::get('contact-us-page', 'contactUsPage')->name('contact-us-page');
            Route::post('addContactUsImage', 'addContactUsImage')->name('addContactUsImage');
            route::get('todo-list','todoList')->name('todo-list');

            Route::get('delete-todo/{id}','deleteTodo')->name('delete-todo');
            Route::get('sendEmail','sendEmail')->name('sendEmail');
            Route::get('convertPDFtoExcel','convertPDFtoExcel')->name('convertPDFtoExcel');

       });
       Route::controller(EnrollmentController::class)->group(function () {
        Route::get('enrollment', 'enrollment')->name('enrollment');
        Route::get('enrollment-status','enrollment_status')->name('enrollment-status');
       });






});
    // });


Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
