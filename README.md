## Requirements:

Follow the [Laravel Setup Instructions](https://laravel.com/docs/12.x/installation) to install PHP, Composer, and Laravel. You'll also need both Node and NPM, or bun installed.


## Setup Instructions

1. Clone the repository.

2. Navigate to the `weather-forecast` folder and install dependencies:
    - `composer install`
    - `npm install`

3. Set up Weatherbit API key:
    - Sign up at [Weatherbit](https://www.weatherbit.io/).
    - Copy `.env.example` to `.env` if it doesn't exist.
    - Add your API key to the `.env` file:
      ```
      WEATHERBIT_API_KEY=your_key_here
      ```
4. Generate the application key:
   - `php artisan key:generate`

5. Run the database migrations:
   - `php artisan migrate`

   Note: The app doesn't use a database, but you'll receive an error otherwise.

6. Run the development server:
    - `composer run dev`

## Usage

Visit `http://localhost:8000` in your web browser to view the application.

This is just a simple page with the dropdown to select a city, the table updates on selecting a new city. There's also a dummy error dropdown to showcase the error handling when the frontend requests an invalid city.

![Home page image](https://i.imgur.com/5kVvFDj.png)

I used tailwindcss for styling, so dark and light modes are supported, as I believe the page defaults to your system's theme. There's also a toggle to switch between modes in the top left.

## Using the console command

You can test the command to fetch and display weather forecasts for multiple cities in your terminal by running `php artisan forecast "city1, city2, city3"` in your terminal.

Make sure to:
- Enclose city names in quotes. 
- Separate multiple cities with commas.

### Example command

This command also displays error message for the invalid city (errorcity123 in this case)
`php artisan forecast "brisbane, sunshine coast, errorcity123, gold coast"`

![Console command image](https://i.imgur.com/lWgx4L7.png)

## Assumptions and design decisions

The app uses the suggested weatherbit API to fetch weather forecasts for the next five days.

Requests to the weatherbit API are handled by `WeatherController.php`. The frontend uses React and Tailwind CSS for the UI, and there's a simple dropdown to select the city to display weather forecasts for.

The console command `forecast` is implemented in `ForecastCommand.php`, which fetches weather data for one or more cities and combines the results into a single table format. For handling multiple cities, There's a separate function in `WeatherController.php` that fetches weather data for each city and returns the combined results. The WeatherController doesn't verify certificates for simplicity, but in a production environment I'd ensure proper SSL verification is in place.

I've implemented graceful error handling for invalid city names on the frontend, and the console command. With messages indicating the user needs to provide a city name, or data was not found for a provided city.

I avoided using LLMs for this task, first checking Laravel documentation / guides / stack overflow for any questions I had. I fell back to using an LLM if I was stuck on a particular issue and couldn't find a solution.


### Additional improvements

For the future, I'd implement a service class to handle HTTP request logic, and not call the API directly from the controller. The current implementation was just to keep the logic simple and straightforward for this small app.

I would likely use additional styling libraries on the frontend, but minimal styling was needed and I wanted to keep the app as simple as possible.

Currently forecasting the next 5 days is hardcoded, but the app could be extended to allow users to select the number of days to forecast. The console output and frontend could be updated to handle a dynamic number of days.
