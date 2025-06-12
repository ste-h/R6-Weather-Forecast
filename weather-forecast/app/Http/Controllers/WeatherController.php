<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function fetchWeatherData($city)
    {
        try {
            // Change to using certificate verification, this is just for testing
            $response = Http::withoutVerifying()->get(config('services.weatherbit.url'), [
                'city' => $city,
                'country' => 'Australia',
                'key' => config('services.weatherbit.key'),
                'days' => 5,
            ])-> throw();

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Weather API request failed',
                ], 502);
            }
            
            return response()->json([
                'success' => true,
                'data' => $response->json(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
