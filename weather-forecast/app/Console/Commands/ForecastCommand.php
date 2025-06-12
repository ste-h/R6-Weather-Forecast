<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\WeatherController;

class ForecastCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast {cities?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows tabulated data of a 5 day forecast for the provided cities - provide multiple cities separated by commas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cities = $this->argument('cities');

        if (empty($cities)) {
            $this->error('Provide a city name');
            return 1;
        }

        $weatherController = new WeatherController();

        $this->info('Fetching weather data');
        $response = $weatherController->fetchWeatherDataMultiple($cities);
        $responseData = $response->getData(true);

        if (!isset($responseData['data'])) {
            $this->error('Failed to fetch weather data');
            return 1;
        }

        $weatherData = $responseData['data'];

        // Filter cities that arent found and display error to the user
        $validCities = [];
        foreach ($weatherData as $city => $data) {
            if (isset($data['error'])) {
                $this->error("Error for {$city}: " . $data['error']);
                continue;
            }
            $validCities[$city] = $data;
        }

        if (empty($validCities)) {
            $this->error('No valid weather data available for any cities');
            return 1;
        }

        $this->displayCombinedForecast($validCities);

        return 0;
    }

    private function displayCombinedForecast($weatherData)
    {
        $this->info("\n5 Day Weather Forecast:");
        $this->line(str_repeat('=', 80));

        $headers = ['City', 'Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];

        $tableData = [];

        foreach ($weatherData as $city => $data) {
            if (!isset($data['forecasts']) || empty($data['forecasts'])) {
                $this->warn("No forecast data available for {$city}");
                continue;
            }

            $row = [strtoupper($city)];

            for ($i = 0; $i < 5; $i++) {
                if (isset($data['forecasts'][$i])) {
                    $forecast = $data['forecasts'][$i];
                    $row[] = sprintf(
                        "Avg: %s\nMax: %s \nMin: %s",
                        $forecast['avg'],
                        $forecast['max'],
                        $forecast['min']
                    );
                } else {
                    $row[] = 'N/A';
                }
            }

            $tableData[] = $row;

            // Adds seperator after each city, except the last
            if ($city !== array_key_last($weatherData)) {
                $tableData[] = array_fill(0, 6, '----------');
            }
        }

        $this->table($headers, $tableData);
    }
}