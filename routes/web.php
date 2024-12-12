<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDataController;

Route::get('/', [UserDataController::class, 'showCrud'])->name('user-crud.show');
Route::get('/user-data', [UserDataController::class, 'index'])->name('user-data.index');
Route::post('/user-data', [UserDataController::class, 'store'])->name('user-data.store');
Route::delete('/user-data/{id}', [UserDataController::class, 'destroy'])->name('user-data.destroy');
