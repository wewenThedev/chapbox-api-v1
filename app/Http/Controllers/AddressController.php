<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MapsService;

use App\Http\Requests\UpdateAddressRequest;
use App\Http\Requests\StoreAddressRequest;

use App\Models\Address;
use App\Models\User;

class AddressController extends Controller
{
    protected MapsService $mapsService;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return Address::all();
        return Address::paginate(2);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        //
        $address = Address::create($request->validated());
        return response()->json($address, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*Address $address*/)
    {
        $address = Address::findOrFail($id);
        return $address;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, string $id /*Address $address*/)
    {
        $address = Address::findOrFail($id);
        $address->update($request->validated());
        return response()->json($address);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*Address $address*/)
    {
        $address = Address::findOrFail($id);
        $address->delete();
        return response()->json(null, 204);

    }

    /*
    -geocode : Convertit une adresse en latitude et longitude.
-reverse-geocode** : Convertit des coordonnées GPS en une adresse physique.
-places** : Recherche des lieux proches d'une position géographique spécifique.
-directions** : Obtenir un itinéraire entre deux adresses ou positions GPS.
-text-search** : Rechercher des lieux en utilisant une chaîne de texte.
-/place-details** : Fournit des détails sur un lieu spécifique via son `place_id`.

*/

    public function reverseGeocode(Request $request)
{
    $lat = $request->input('lat');
    $lng = $request->input('lng');
    If (!$lat || !$lng) {
        return response()->json(['error' => 'Latitude and Longitude parameters are required'], 400);
    }

    $addressData = $this->mapsService->reverseGeocode($lat, $lng);
    return response()->json($addressData);
}

public function nearbyPlaces(Request $request)
{
    $lat = $request->input('lat');
    $lng = $request->input('lng');
    $radius = $request->input('radius', 1000); // rayon par défaut 1000 mètres
    $type = $request->input('type', 'restaurant'); // type de lieu par défaut “restaurant”

    If (!$lat || !$lng) {
        Return response()->json(['error' => 'Latitude and Longitude parameters are required'], 400);
    }

    $places = $this->mapsService->getNearbyPlaces($lat, $lng, $radius, $type);
    Return response()->json($places);
}

public function getDirections(Request $request)
{
    $origin = $request->input('origin');
    $destination = $request->input('destination');

    If (!$origin || !$destination) {
        Return response()->json(['error' => 'Origin and Destination parameters are required'], 400);
    }

    $directions = $this->mapsService->getDirections($origin, $destination);
    Return response()->json($directions);
}

public function textSearch(Request $request)
{
    $query = $request->input('query');
    If (!$query) {
        Return response()->json(['error' => 'Query parameter is required'], 400);
    }

    $places = $this->mapsService->textSearch($query);
    Return response()->json($places);
}

public function placeDetails(Request $request)
{
    $placeId = $request->input('place_id');
    If (!$placeId) {
        Return response()->json(['error' => 'Place ID parameter is required'], 400);
    }

    $placeDetails = $this->mapsService->getPlaceDetails($placeId);
    Return response()->json($placeDetails);
}


}
