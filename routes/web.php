<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StaffWebController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// Group admin/staff web pages behind auth + permission
Route::middleware(['auth']) // user must log in
    ->prefix('admin')       // URLs look like /admin/staff
    ->name('admin.')        // route names like admin.staff.index
    ->group(function () {

        // List staff (needs 'view users')
        Route::get('/staff', [StaffWebController::class, 'index'])
            ->middleware('can:view users')
            ->name('staff.index');

        // Create staff (needs 'create users')
        Route::get('/staff/create', [StaffWebController::class, 'create'])
            ->middleware('can:create users')
            ->name('staff.create');
        Route::post('/staff', [StaffWebController::class, 'store'])
            ->middleware('can:create users')
            ->name('staff.store');

        // Edit/update staff (needs 'edit users')
        Route::get('/staff/{id}/edit', [StaffWebController::class, 'edit'])
            ->middleware('can:edit users')
            ->name('staff.edit');
        Route::patch('/staff/{id}', [StaffWebController::class, 'update'])
            ->middleware('can:edit users')
            ->name('staff.update');

        // Assign manager (needs 'manage reporting lines')
        Route::get('/staff/{id}/manager', [StaffWebController::class, 'managerForm'])
            ->middleware('can:manage reporting lines')
            ->name('staff.manager.form');
        Route::patch('/staff/{id}/manager', [StaffWebController::class, 'assignManager'])
            ->middleware('can:manage reporting lines')
            ->name('staff.manager.assign');

        // Reset password (admin/super-admin via 'reset passwords')
        Route::post('/staff/{id}/reset-password', [StaffWebController::class, 'resetPassword'])
            ->middleware('can:reset passwords')
            ->name('staff.reset.password');
    });
