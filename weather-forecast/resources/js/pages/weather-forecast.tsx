import { Head } from '@inertiajs/react';
import BasicWeatherTest from '@/components/weather-test';

export default function Homepage() {
    return (
        <>
            <Head title="Home" />
            <div className="mx-auto flex w-full max-w-7xl flex-1 flex-col px-4 sm:px-6 lg:px-8">
                <div>
                    <h1>Temp</h1>
                    <BasicWeatherTest />
                </div>
            </div>
        </>
    );
}
