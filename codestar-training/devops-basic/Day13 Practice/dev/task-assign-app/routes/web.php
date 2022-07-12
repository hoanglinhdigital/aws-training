<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AmountConfirmController;
use App\Http\Controllers\AssigneeListController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\WorkPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ProcessMethodController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffInfoController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\TimesheetStatisticsController;
use App\Http\Controllers\ProjectPlanController;
use App\Http\Controllers\BackupMandayController;
use App\Http\Controllers\JobAssignController;
use App\Http\Controllers\FreeTimeController;

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

Route::redirect('/', '/jobs?type=handling', 301);


Route::get('/jobs/update-history', function () {
    return view('jobs.update-history');
});


Route::get('/project-types/create', function () {
    return view('welcome');
});
Route::get('/priorities', function () {
    return view('priority');
});

Route::get('/configurations', function () {
    return view('configuration');
});


//================================== ROUTE VIEW =====================================================//

// Project
Route::prefix('project')->group(function () {
    Route::get('/', [ProjectController::class, 'list'])->name('project.list');
    Route::post('/store', [ProjectController::class, 'store'])->name('project.store');
    Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('project.edit');
    Route::post('/update/{id}', [ProjectController::class, 'update'])->name('project.update');
    Route::get('/delete/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');
});

// Project-Type
Route::prefix('project-type')->group(function () {
    Route::get('/', [ProjectTypeController::class, 'list'])->name('project-type.list');
    Route::post('/store', [ProjectTypeController::class, 'store'])->name('project-type.store');
    Route::get('/edit/{id}', [ProjectTypeController::class, 'edit'])->name('project-type.edit');
    Route::post('/update/{id}', [ProjectTypeController::class, 'update'])->name('project-type.update');
    Route::get('/delete/{id}', [ProjectTypeController::class, 'destroy'])->name('project-type.destroy');
});

// Priorities
Route::prefix('priority')->group(function () {
    Route::get('/', [PriorityController::class, 'list'])->name('priority.list');
    Route::post('/store', [PriorityController::class, 'store'])->name('priority.store');
    Route::get('/edit/{id}', [PriorityController::class, 'edit'])->name('priority.edit');
    Route::post('/update/{id}', [PriorityController::class, 'update'])->name('priority.update');
    Route::get('/delete/{id}', [PriorityController::class, 'destroy'])->name('priority.destroy');
});

//Skills
Route::prefix('skill')->group(function () {
    Route::get('/', [SkillController::class, 'list'])->name('skill.list');
    Route::post('/store', [SkillController::class, 'store'])->name('skill.store');
    Route::get('/edit/{id}', [SkillController::class, 'edit'])->name('skill.edit');
    Route::post('/update/{id}', [SkillController::class, 'update'])->name('skill.update');
    Route::get('/delete/{id}', [SkillController::class, 'destroy'])->name('skill.destroy');
});

// Process Method
Route::prefix('process-method')->group(function () {
    Route::get('/', [ProcessMethodController::class, 'list'])->name('process-method.list');
    Route::post('/store', [ProcessMethodController::class, 'store'])->name('process-method.store');
    Route::get('/edit/{id}', [ProcessMethodController::class, 'edit'])->name('process-method.edit');
    Route::post('/update/{id}', [ProcessMethodController::class, 'update'])->name('process-method.update');
    Route::get('/delete/{id}', [ProcessMethodController::class, 'destroy'])->name('process-method.destroy');
});

// Config
Route::prefix('configurations')->group(function () {
    Route::get('/', [ConfigController::class, 'list'])->name('config.list');
    Route::post('/update', [ConfigController::class, 'update'])->name('config.update');
});

Route::get('/register', [RegisterController::class, 'register']);
Route::post('/register', [RegisterController::class, 'store'])->name('register');

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/forget-password', [ForgotPasswordController::class, 'getEmail']);
Route::post('/forget-password', [ForgotPasswordController::class, 'postEmail'])->name('forgotPassword');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'getPassword']);
Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('resetPassword');


Route::get('/accounts/pending', [AccountController::class, 'getPendingAccounts'])->name('accounts.pending');
Route::post('/accounts/activate', [AccountController::class, 'activateAccounts'])->name('accounts.activate');

Route::get('/staff-info/{id}', [StaffInfoController::class, 'show'])->name('staff_info.show');
Route::post('/staff-info/{id}', [StaffInfoController::class, 'update'])->name('staff_info.update');

Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
Route::post('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');

//Time Sheet
Route::prefix('/timesheets')->group(function () {
    Route::get('/', [TimeSheetController::class, 'create'])->name('timesheet.create');
    Route::post('/store', [TimeSheetController::class, 'store'])->name('timesheet.store');
    Route::get('/edit/{id}', [TimeSheetController::class, 'edit'])->name('timesheet.edit');
    Route::post('/update/{id}', [TimeSheetController::class, 'update'])->name('timesheet.update');
    Route::get('/delete/{id}', [TimeSheetController::class, 'destroy'])->name('timesheet.destroy');
});

//Timesheet Statictics
Route::get('/timesheet-statistics', [TimesheetStatisticsController::class, 'list'])->name('timesheet-statis.list');
Route::post('/timesheet-statistics-search', [TimesheetStatisticsController::class, 'search'])->name('timesheet-statis.search');

//Project Plan
Route::group(['middleware' => ['auth']], function () {
    Route::get('/project-plan', [ProjectPlanController::class, 'list'])->name('project-plan.list');
    Route::get('/project-plan/{id}/jobs', [ProjectPlanController::class, 'queryJobs'])->name('project-plan.queryJobs');
    Route::post('/project-plan-search', [ProjectPlanController::class, 'search'])->name('project-plan.search');
});

//Backup Manday
Route::get('/backup-manday', [BackupMandayController::class, 'list'])->name('backup-manday.list');
Route::post('/backup-manday-search', [BackupMandayController::class, 'search'])->name('backup-manday.search');

// Free time
Route::prefix('free-time')->group(function () {
    Route::get('/', [FreeTimeController::class, 'list'])->name('free-time.list');
    Route::post('/search', [FreeTimeController::class, 'search'])->name('free-time.search');
});

Route::get('assignee-list', [AssigneeListController::class, 'index'])->name('assignee-list.index');
Route::post('assignee-list', [AssigneeListController::class, 'action'])->name('assignee-list.action');

Route::get('amount-confirms/create', [AmountConfirmController::class, 'create'])->name('amount-confirms.create');
Route::post('amount-confirms', [AmountConfirmController::class, 'action'])->name('amount-confirms.action');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/jobs/create/{jobId?}', [JobsController::class, 'create'])->name('jobs.create');
    Route::post('/jobs/search', [JobsController::class, 'search'])->name('jobs.search');
    Route::get('/jobs', [JobsController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{jobId}', [JobsController::class, 'detail'])->name('jobs.detail');
    Route::post('/jobs', [JobsController::class, 'action'])->name('jobs.action');
    Route::post('/jobs/detail', [JobsController::class, 'detailAction'])->name('jobs.detailAction');
    Route::post('/jobs/update-status', [JobsController::class, 'updateStatus'])->name('jobs.updateStatus');
    Route::post('/jobs/update-assignee-list', [JobsController::class, 'updateAssigneeList'])->name('jobs.updateAssigneeList');
});

Route::post('job-assigns/job-detail', [JobAssignController::class, 'jobDetail'])->name('job-assigns.jobDetail');
Route::post('job-assigns/update-status', [JobAssignController::class, 'updateStatus'])->name('job-assigns.updateStatus');
Route::post('job-assign/delete', [JobAssignController::class, 'delete'])->name('job-assigns.delete');

Route::get('workplans/create/{jobId}', [WorkPlanController::class, 'create'])->name('workplans.create');
Route::post('workplans', [WorkPlanController::class, 'store'])->name('workplans.store');
Route::post('workplans/delete', [WorkPlanController::class, 'delete'])->name('workplans.delete');
