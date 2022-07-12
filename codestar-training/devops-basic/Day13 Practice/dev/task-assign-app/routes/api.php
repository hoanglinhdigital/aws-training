<?php

use App\Http\Controllers\AmountConfirmController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\AssigneeListController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\JobAssignController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\ProcessMethodController;
use App\Http\Controllers\UpdateJobHistoryController;
use App\Http\Controllers\WorkPlanController;
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

Route::get('department', [DepartmentController::class, 'index'])->name('deparment.list');

Route::get('jobs/{id}', [JobsController::class, 'show'])->name('jobs.show');

Route::get('update-job-histories', [UpdateJobHistoryController::class, 'index'])->name('update_job_histories.index');

Route::get('assignee-list/{jobId}', [AssigneeListController::class, 'show'])->name('assignee-list.show');

Route::get('workplans/{jobId}/{assigneeId?}', [WorkPlanController::class, 'show'])->name('workplans.show');

Route::get('job-assigns', [JobAssignController::class, 'index'])->name('job-assigns.index');

Route::get('amount-confirms', [AmountConfirmController::class, 'index'])->name('amount_confirms.index');
Route::get('amount-confirms/{id}', [AmountConfirmController::class, 'show'])->name('amount-confirms.show');
Route::post('amount-confirms', [AmountConfirmController::class, 'queryAmountConfirm'])->name('amount-confirms.query');

Route::get('process-methods', [ProcessMethodController::class, 'queryProcessMethod'])->name('process-methods.query');

Route::get('skill', [SkillController::class, 'list']);
Route::post('skill', [SkillController::class, 'store']);
Route::get('skill/{id}', [SkillController::class, 'edit']);
Route::put('skill/{id}', [SkillController::class, 'update']);
Route::delete('skill/{id}', [SkillController::class, 'destroy']);
