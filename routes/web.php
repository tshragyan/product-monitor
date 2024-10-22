<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/verify', [UserController::class, 'verify'])->name('verify');
