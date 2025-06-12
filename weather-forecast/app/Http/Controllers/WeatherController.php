<?php

namespace App\Http\Controllers;

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

            $weatherData = $response->json();

            $FilteredData = [
                'forecasts' => collect($weatherData['data']) -> map(function ($day)  {
                    return [
                        'date' => $day['valid_date'],
                        'min' => $day['min_temp'],
                        'max' => $day['max_temp'],
                        'avg' => round(($day['min_temp'] + $day['max_temp']) / 2, 1),
                    ];
                }) -> toArray(),
            ];
            
            return response()->json([
                'data' => $FilteredData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
