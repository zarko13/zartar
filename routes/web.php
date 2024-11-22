<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index']);
Route::post('async/execute-command', [PublicController::class, 'asyncExecuteCommand']);
