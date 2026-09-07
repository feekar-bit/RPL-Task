<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\Admin\GuruManagementController;
use App\Http\Controllers\Guru\TaskController;
use App\Http\Controllers\Siswa\SiswaTaskController;
use App\Http\Controllers\Siswa\TaskSubmissionController;
use App\Http\Controllers\Guru\SubmissionManagementController;
use App\Http\Controllers\Siswa\ProfileController as SiswaProfileController;
use App\Http\Controllers\Guru\ProfileController as GuruProfileController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\GuruApprovalController;
use App\Http\Controllers\Guru\StudentController;
use App\Http\Controllers\Admin\TeacherController;

Route::get('/', function () {
    return view('welcome');
});


// ================= LOGIN =================

Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/login', [AuthController::class, 'loginProcess'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


// ================= REGISTER =================

Route::get('/register', [AuthController::class, 'chooseRole'])
    ->name('register');


// siswa
Route::get('/register/siswa', [AuthController::class, 'registerSiswa'])
    ->name('register.siswa');

Route::post('/register/siswa', [AuthController::class, 'registerSiswaProcess'])
    ->name('register.siswa.process');


// guru
Route::get('/register/guru', [AuthController::class, 'registerGuru'])
    ->name('register.guru');

Route::post('/register/guru', [AuthController::class, 'registerGuruProcess'])
    ->name('register.guru.process');


// ================= DASHBOARD =================

// admin
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware('role:admin');


// guru
Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])
    ->middleware('role:guru');


// siswa
Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])
    ->middleware('role:siswa');


// ================= ADMIN GURU MANAGEMENT =================

Route::get('/admin/guru/pending', [GuruManagementController::class, 'pending'])
    ->middleware('role:admin');

Route::post('/admin/guru/approve/{id}', [GuruManagementController::class, 'approve'])
    ->middleware('role:admin');

Route::delete('/admin/guru/delete/{id}', [GuruManagementController::class, 'delete'])
    ->middleware('role:admin');


// ================= GURU TASK =================

Route::get('/guru/tasks', [TaskController::class, 'index'])
    ->middleware('role:guru');

Route::get('/guru/tasks/create', [TaskController::class, 'create'])
    ->middleware('role:guru');

Route::post('/guru/tasks/store', [TaskController::class, 'store'])
    ->middleware('role:guru');

Route::get('/guru/tasks/edit/{id}', [TaskController::class, 'edit'])
    ->middleware('role:guru');

Route::put('/guru/tasks/update/{id}', [TaskController::class, 'update'])
    ->middleware('role:guru');

Route::delete('/guru/tasks/delete/{id}', [TaskController::class, 'destroy'])
    ->middleware('role:guru');

// ================= SISWA TASK =================

Route::get('/siswa/tasks', [SiswaTaskController::class, 'index'])
    ->middleware('role:siswa');

Route::get('/siswa/tasks/{id}', [SiswaTaskController::class, 'show'])
    ->middleware('role:siswa');


// ================= SISWA SUBMISSION =================

Route::get('/siswa/tasks/{taskId}/submit',
    [TaskSubmissionController::class, 'create'])
    ->middleware('role:siswa');

Route::post('/siswa/tasks/{taskId}/submit',
    [TaskSubmissionController::class, 'store'])
    ->middleware('role:siswa');


// ================= GURU SUBMISSION =================

Route::get('/guru/tasks/{taskId}/submissions',
    [SubmissionManagementController::class, 'index'])
    ->middleware('role:guru');

Route::get('/guru/submissions/{id}/edit',
    [SubmissionManagementController::class, 'edit'])
    ->middleware('role:guru');

Route::put('/guru/submissions/{id}/update',
    [SubmissionManagementController::class, 'update'])
    ->middleware('role:guru');


// ================= PROFILE SISWA =================

Route::get('/siswa/profile',
    [SiswaProfileController::class, 'index'])
    ->middleware('role:siswa');

Route::put('/siswa/profile/update',
    [SiswaProfileController::class, 'update'])
    ->middleware('role:siswa');


// ================= PROFILE GURU =================

Route::get('/guru/profile',
    [GuruProfileController::class, 'index'])
    ->middleware('role:guru');

Route::put('/guru/profile/update',
    [GuruProfileController::class, 'update'])
    ->middleware('role:guru');


// ================= PROFILE ADMIN =================

Route::get('/admin/profile',
    [AdminProfileController::class, 'index'])
    ->middleware('role:admin');

Route::put('/admin/profile/update',
    [AdminProfileController::class, 'update'])
    ->middleware('role:admin');


// ================= APPROVAL GURU =================

Route::get('/admin/guru-approval',
    [GuruApprovalController::class, 'index'])
    ->middleware('role:admin');

Route::put('/admin/guru-approval/{id}',
    [GuruApprovalController::class, 'approve'])
    ->middleware('role:admin');


// ================= DATA SISWA =================

Route::get('/guru/students',
    [StudentController::class, 'index'])
    ->middleware('role:guru');


// ================= HISTORY TASK GURU =================

Route::get('/guru/tasks/history',
    [TaskController::class, 'history'])
    ->middleware('role:guru');


// ================= DATA GURU =================

Route::get('/admin/teachers',
    [TeacherController::class, 'index'])
    ->middleware('role:admin');