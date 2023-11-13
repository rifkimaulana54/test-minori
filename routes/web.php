<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//Training Karyawan
Route::get('/training-karyawan', [App\Http\Controllers\TrainingKaryawanController::class, 'index'])->name('training-karyawan');
Route::post('/training-karyawan/getKaryawanList', [App\Http\Controllers\TrainingKaryawanController::class, 'getKaryawanList'])->name('training-karyawan/getKaryawanList');
Route::get('/training-karyawan/create', [App\Http\Controllers\TrainingKaryawanController::class, 'create'])->name('training-karyawan/create');
Route::post('/training-karyawan/store', [App\Http\Controllers\TrainingKaryawanController::class, 'store'])->name('training-karyawan/store');
Route::get('/training-karyawan/{id}', [App\Http\Controllers\TrainingKaryawanController::class, 'edit'])->name('training-karyawan/edit');
Route::post('/training-karyawan/update', [App\Http\Controllers\TrainingKaryawanController::class, 'update'])->name('training-karyawan/update');
Route::post('/training-karyawan/delete/{id}', [App\Http\Controllers\TrainingKaryawanController::class, 'destroy'])->name('karyawan/delete');

//Karyawan
Route::get('/karyawan', [App\Http\Controllers\KaryawanController::class, 'index'])->name('karyawan');
Route::post('/karyawan/getKaryawanList', [App\Http\Controllers\KaryawanController::class, 'getKaryawanList'])->name('karyawan/getKaryawanList');
Route::get('/karyawan/create', [App\Http\Controllers\KaryawanController::class, 'create'])->name('karyawan/create');
Route::post('/karyawan/store', [App\Http\Controllers\KaryawanController::class, 'store'])->name('karyawan/store');
Route::get('/karyawan/{id}', [App\Http\Controllers\KaryawanController::class, 'edit'])->name('karyawan/edit');
Route::post('/karyawan/update', [App\Http\Controllers\KaryawanController::class, 'update'])->name('karyawan/update');
Route::post('/karyawan/delete/{id}', [App\Http\Controllers\KaryawanController::class, 'destroy'])->name('karyawan/delete');


//Training
Route::get('/training/create', [App\Http\Controllers\TrainingController::class, 'create'])->name('training/create');
Route::post('/training/store', [App\Http\Controllers\TrainingController::class, 'store'])->name('training/store');
// Route::post('/training/update', [App\Http\Controllers\TrainingController::class, 'update'])->name('training/update');
// Route::get('/training/{id}', [App\Http\Controllers\TrainingController::class, 'show'])->name('training/show');
// Route::post('/training/delete/{id}', [App\Http\Controllers\TrainingController::class, 'destroy'])->name('training/delete');
