<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function fetchWeatherData($city)
    {
        try {
            // Change to using certificate verification, this is just for testing
            $response = Http::withoutVerifying()->get('https://api.weatherbit.io/v2.0/forecast/daily', [
                'city' => $city,
                'country' => 'Australia',
                'key' => config('services.weatherbit.key'),
                'days' => 5,
            ])->throw();

            $weatherData = $response->json();

            $FilteredData = [
                'forecasts' => collect($weatherData['data'])->map(function ($day) {
                    return [
                        'date' => $day['valid_date'],
                        'min' => $day['min_temp'],
                        'max' => $day['max_temp'],
                        'avg' => round(($day['min_temp'] + $day['max_temp']) / 2, 1),
                    ];
                })->toArray(),
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

    public function fetchWeatherDataMultiple($cities)
    {
        $cityList = explode(',', $cities);
        $cityList = array_map('trim', $cityList);
        $results = [];

        foreach ($cityList as $city) {
            if (empty($city))
                continue;

            $response = $this->fetchWeatherData($city);
            $responseData = $response->getData(true);

            if (isset($responseData['error'])) {
                $results[$city] = ['error' => "City '{$city}' not found"];
            } else {
                $results[$city] = $responseData['data'];
            }
        }

        return response()->json([
            'data' => $results,
        ]);
    }

}

