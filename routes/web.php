<?php

use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StaffUserController;
use App\Http\Controllers\Approver\ComplaintController as ApproverComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicComplaintController;
use App\Http\Controllers\Staff\AssignedComplaintController;
use App\Http\Controllers\User\ComplaintController as UserComplaintController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicComplaintController::class, 'index'])->name('home');
Route::get('/feed/{complaint}', [PublicComplaintController::class, 'show'])->name('feed.show');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/feed/{complaint}/react', [PublicComplaintController::class, 'react'])
        ->name('feed.react');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
        Route::patch('/complaints/{complaint}/assignments', [AdminComplaintController::class, 'updateAssignments'])
            ->name('complaints.assignments');

        Route::get('/staff/{role}', [StaffUserController::class, 'index'])->name('staff.index');
        Route::get('/staff/{role}/create', [StaffUserController::class, 'create'])->name('staff.create');
        Route::post('/staff/{role}', [StaffUserController::class, 'store'])->name('staff.store');
        Route::get('/staff/{role}/{user}/edit', [StaffUserController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{role}/{user}', [StaffUserController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{role}/{user}', [StaffUserController::class, 'destroy'])->name('staff.destroy');
    });

    Route::middleware('role:approver')->prefix('approver')->name('approver.')->group(function () {
        Route::get('/complaints', [ApproverComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [ApproverComplaintController::class, 'show'])->name('complaints.show');
        Route::post('/complaints/{complaint}/decide', [ApproverComplaintController::class, 'decide'])->name('complaints.decide');
    });

    Route::middleware('role:lawyer')->prefix('lawyer')->name('lawyer.')->group(function () {
        Route::get('/complaints', [AssignedComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [AssignedComplaintController::class, 'show'])->name('complaints.show');
    });

    Route::middleware('role:counselor')->prefix('counselor')->name('counselor.')->group(function () {
        Route::get('/complaints', [AssignedComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [AssignedComplaintController::class, 'show'])->name('complaints.show');
    });

    Route::middleware('role:law_enforcer')->prefix('law-enforcer')->name('law-enforcer.')->group(function () {
        Route::get('/complaints', [AssignedComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [AssignedComplaintController::class, 'show'])->name('complaints.show');
    });

    Route::middleware('role:user')->prefix('my')->name('user.')->group(function () {
        Route::get('/complaints', [UserComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/create', [UserComplaintController::class, 'create'])->name('complaints.create');
        Route::post('/complaints', [UserComplaintController::class, 'store'])->name('complaints.store');
        Route::get('/complaints/{complaint}', [UserComplaintController::class, 'show'])->name('complaints.show');
        Route::get('/directory', [UserComplaintController::class, 'directory'])->name('directory');
    });
});

require __DIR__.'/auth.php';
