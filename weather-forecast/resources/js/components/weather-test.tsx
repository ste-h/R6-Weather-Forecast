import React, { useState, useEffect } from 'react';

const BasicWeatherTest = () => {
const [data, setData] = useState<unknown>(null);

  useEffect(() => {
    fetch('http://localhost:8000/api/weather/Brisbane')
      .then(res => res.json())
      .then(setData)
      .catch(err => setData({ success: false, error: err.message }))
  }, []);


  return (
    <div>
      <h1>Weather</h1>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
};

export default BasicWeatherTest;
