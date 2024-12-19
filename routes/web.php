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
Route::get('/user/create', [App\Http\Controllers\UserController::class, 'create'])->name('user.create');
Route::post('/user/store', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
Route::get('/user/index', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
Route::get('/user/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');
Route::post('/user/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
Route::get('/user/delete/{id}', [App\Http\Controllers\UserController::class, 'destory'])->name('user.delete');

Route::get('/company/create', [App\Http\Controllers\CompanyController::class, 'create'])->name('company.create');
Route::get('/company/states/{countryId}', [App\Http\Controllers\CompanyController::class, 'getStates'])->name('company.getStates');
Route::get('/company/cities/{stateId}', [App\Http\Controllers\CompanyController::class, 'getCities'])->name('company.getCities');
Route::post('/company/store', [App\Http\Controllers\CompanyController::class, 'store'])->name('company.store');
Route::get('/company/index', [App\Http\Controllers\CompanyController::class, 'index'])->name('company.index');
Route::get('/company/edit/{id}', [App\Http\Controllers\CompanyController::class, 'edit'])->name('company.edit');
Route::post('/company/update/{id}', [App\Http\Controllers\CompanyController::class, 'update'])->name('company.update');
Route::get('/company/delete/{id}', [App\Http\Controllers\CompanyController::class, 'destory'])->name('company.delete');




Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
