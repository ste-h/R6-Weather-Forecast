import { Head } from '@inertiajs/react';
import BasicWeatherTest from '@/components/weather-forecast';
import AppearanceToggleDropdown from '@/components/appearance-dropdown';  

export default function Homepage() {
    return (
        <>
            <Head title="Home" />
            <div className="mx-auto">
                <div>
                    <AppearanceToggleDropdown/>
                    <BasicWeatherTest />
                </div>
            </div>
        </>
    );
}
