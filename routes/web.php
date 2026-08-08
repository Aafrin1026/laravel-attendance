<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest.custom')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth.admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',          [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/students',           [AdminController::class, 'students'])->name('students');
    Route::post('/students',          [AdminController::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{id}',      [AdminController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{id}',   [AdminController::class, 'destroyStudent'])->name('students.destroy');

    Route::get('/subjects',           [AdminController::class, 'subjects'])->name('subjects');
    Route::post('/subjects',          [AdminController::class, 'storeSubject'])->name('subjects.store');
    Route::put('/subjects/{id}',      [AdminController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/subjects/{id}',   [AdminController::class, 'destroySubject'])->name('subjects.destroy');

    Route::get('/departments',        [AdminController::class, 'departments'])->name('departments');
    Route::post('/departments',       [AdminController::class, 'storeDepartment'])->name('departments.store');
    Route::put('/departments/{id}',   [AdminController::class, 'updateDepartment'])->name('departments.update');
    Route::delete('/departments/{id}',[AdminController::class, 'destroyDepartment'])->name('departments.destroy');

    Route::get('/attendance',         [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/mark',    [AttendanceController::class, 'markForm'])->name('attendance.mark');
    Route::post('/attendance/bulk',   [AttendanceController::class, 'bulkSave'])->name('attendance.bulk');
    Route::get('/attendance/view',    [AttendanceController::class, 'view'])->name('attendance.view');
    Route::get('/attendance/data',    [AttendanceController::class, 'getData'])->name('attendance.data');
});

Route::middleware('auth.student')->prefix('student')->name('student.')->group(function () {
    Route::get('/attendance', [StudentController::class, 'myAttendance'])->name('attendance');
});
