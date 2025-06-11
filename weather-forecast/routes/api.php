<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

Route::get('/weather/{city}', [WeatherController::class, 'fetchWeatherData']);
