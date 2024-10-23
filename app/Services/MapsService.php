<?php

namespace App\Services;

//use Illuminate\Http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AddressController;

class MapsService{
    
    protected /*string*/ $apiKey;

    Public function __construct()
    {
        $this->apiKey = config('services.google_maps.api_key');
    }


    public function reverseGeocode($lat, $lng)
{
    $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
        'latlng' => "$lat,$lng",
        'key' => $this->apiKey
    ]);

    return $response->json();
}

public function getNearbyPlaces($lat, $lng, $radius, $type)
{
    $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
        'location' => "$lat,$lng",
        'radius' => $radius,
        'type' => $type,
        'key' => $this->apiKey
    ]);

    return $response->json();
}

public function getDirections($origin, $destination)
{
    $response = Http::get('https://maps.googleapis.com/maps/api/directions/json', [
        'origin' => $origin,
        'destination' => $destination,
        'key' => $this->apiKey
    ]);

    return $response->json();
}

public function textSearch($query)
{
    $response = Http::get('https://maps.googleapis.com/maps/api/place/textsearch/json', [
        'query' => $query,
        'key' => $this->apiKey
    ]);

    return $response->json();
}

public function getPlaceDetails($placeId)
{
    $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
        'place_id' => $placeId,
        'key' => $this->apiKey
    ]);

    return $response->json();
}

}
