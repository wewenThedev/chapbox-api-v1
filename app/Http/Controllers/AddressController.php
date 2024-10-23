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
    protected /*MapsService*/ $mapsService;
    public function __construct(MapsService $mapsService)
    {
        $this->mapsService = $mapsService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Address::create([
            'name' => 'Siège Erevan',
            'fullAddress' => 'Cotonou à préciser',
            'latitude' => 6.356737,
            'longitude' => 2.440927,
        ]);

        Address::create([
            'name' => 'Siège Mont Sinaï',
            'fullAddress' => 'Cotonou à préciser',
            'latitude' => 6.365976,
            'longitude' => 2.404382,
        ]);

        Address::create([
            'name' => 'Siège WorldMarket',
            'fullAddress' => 'Cotonou à préciser',
            'latitude' => 6.371918,
            'longitude' => 2.386040,
        ]);

        $address = Address::all();
        return response()->json($address, 200);
        //return Address::paginate(2);
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
        if (!$lat || !$lng) {
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

        if (!$lat || !$lng) {
            return response()->json(['error' => 'Latitude and Longitude parameters are required'], 400);
        }

        $places = $this->mapsService->getNearbyPlaces($lat, $lng, $radius, $type);
        return response()->json($places);
    }

    public function getDirections(Request $request)
    {
        $origin = $request->input('origin');
        $destination = $request->input('destination');

        if (!$origin || !$destination) {
            return response()->json(['error' => 'Origin and Destination parameters are required'], 400);
        }

        $directions = $this->mapsService->getDirections($origin, $destination);
        return response()->json($directions);
    }

    public function textSearch(Request $request)
    {
        $query = $request->input('query');
        if (!$query) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }

        $places = $this->mapsService->textSearch($query);
        return response()->json($places);
    }

    public function placeDetails(Request $request)
    {
        $placeId = $request->input('place_id');
        if (!$placeId) {
            return response()->json(['error' => 'Place ID parameter is required'], 400);
        }

        $placeDetails = $this->mapsService->getPlaceDetails($placeId);
        return response()->json($placeDetails);
    }
}
