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
        $geoResponse = Http::get("http://ip-api.com/json/{$clientIp}");
        $locationData = $geoResponse->json();

        $location = $locationData['city'] ?? 'Unknown Location';
        $temperature = 11; // Static temperature for simplicity

        return response()->json([
            'client_ip' => $clientIp,
            'location' => $location,
            'greeting' => "Hello, {$visitorName}!, the temperature is {$temperature} degrees Celsius in {$location}",
        ]);
    }
}
