import { useEffect, useState } from 'react';

interface WeatherDay {
    date: string;
    min: number;
    max: number;
    avg: number;
}

interface WeatherResponse {
    data: {
        forecasts: WeatherDay[];
    };
}

function WeatherForecast() {
    const [city, setCity] = useState('Brisbane');
    const [data, setData] = useState<WeatherResponse | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        setLoading(true);
        fetch(`http://localhost:8000/api/weather/${city}`)
            .then((res) => res.json())
            .then((data) => {
                setData(data);
                setLoading(false);
            })
            .catch(() => setLoading(false));
    }, [city]);

    const headers = ['City', 'Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];

    return (
        <div className="p-6 dark:text-white">
            <h1 className="mb-4 text-2xl font-bold dark:text-white">Weather Forecast</h1>

            <select 
                value={city} 
                onChange={(e) => setCity(e.target.value)} 
                className="mb-6 rounded-md border border-gray-300 p-2 
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white"
            >
                <option value="Brisbane">Brisbane</option>
                <option value="Gold Coast">Gold Coast</option>
                <option value="Sunshine Coast">Sunshine Coast</option>
                <option value="Gives Error">Gives Error (demo purposes)</option>
            </select>

            {loading ? (
                <p className="dark:text-gray-300">Loading...</p>
            ) : data?.data?.forecasts ? (
                <table className="w-full border-collapse border border-gray-300 bg-white shadow-sm
                                  dark:border-gray-600 dark:bg-gray-800">
                    <thead className="bg-gray-600 text-white dark:bg-gray-700">
                        <tr>
                            {headers.map((header) => (
                                <th key={header} 
                                    className="border border-gray-300 px-4 py-3 text-left font-semibold
                                               dark:border-gray-600 dark:text-gray-100">
                                    {header}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td className="border border-gray-300 px-4 py-3 font-medium
                                           dark:border-gray-600 dark:text-gray-200">
                                {city}
                            </td>
                            {data.data.forecasts.map((day: WeatherDay, i: number) => (
                                <td key={i} 
                                    className="border border-gray-300 px-4 py-3
                                               dark:border-gray-600">
                                    <div className="text-sm">
                                        <div className="dark:text-gray-200">
                                            <span className="font-medium dark:text-gray-100">Avg:</span> {day.avg}°
                                        </div>
                                        <div className="dark:text-gray-200">
                                            <span className="font-medium dark:text-gray-100">Max:</span> {day.max}°
                                        </div>
                                        <div className="dark:text-gray-200">
                                            <span className="font-medium dark:text-gray-100">Min:</span> {day.min}°
                                        </div>
                                    </div>
                                </td>
                            ))}
                        </tr>
                    </tbody>
                </table>
            ) : (
                <p className="dark:text-red-400">Failed to load weather data</p>
            )}
        </div>
    );
}

export default WeatherForecast;