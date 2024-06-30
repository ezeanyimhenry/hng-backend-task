<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function hello(Request $request)
    {
        $visitorName = $request->query('visitor_name', 'Guest');
        $clientIp = $request->ip();
        
        // Get location information from ip-api.com
        $geoResponse = Http::get("http://ip-api.com/json/{$clientIp}");
        $locationData = $geoResponse->json();

        $location = $locationData['city'] ?? 'Unknown Location';
        $country = $locationData['country'] ?? 'Unknown Country';

        // Get weather information from OpenWeatherMap
        $apiKey = config('api.open_weather_api');
        $weatherResponse = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $location,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);
        $weatherData = $weatherResponse->json();
        $temperature = $weatherData['main']['temp'] ?? 'N/A';

        return response()->json([
            'client_ip' => $clientIp,
            'location' => $location,
            'country' => $country,
            'greeting' => "Hello, {$visitorName}!, the temperature is {$temperature} degrees Celsius in {$location}, {$country}"
        ]);
    }
}
