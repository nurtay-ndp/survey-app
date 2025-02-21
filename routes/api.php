<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;

Route::get('/questions', [SurveyController::class, 'index']);
Route::post('/response', [SurveyController::class, 'store']);

