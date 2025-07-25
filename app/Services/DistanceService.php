<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DistanceService
{
    protected $googleApiKey;
    protected $originAddress = 'Rua José Alves da Silva, 429, Parque Novo Santo Amaro, São Paulo, SP';


    public function __construct()
    {
        $this->googleApiKey = env('GOOGLE_MAPS_API_KEY');
    }

    public function getCoordinates($address)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json";
        $response = Http::get($url, [
            'address' => $address,
            'key' => $this->googleApiKey,
        ]);

        $data = $response->json();

        if (!empty($data['results'])) {
            $location = $data['results'][0]['geometry']['location'];
            return [$location['lat'], $location['lng']];
        }

        return null;
    }

    public function getDistanceInKm($address2)
    {
        $coords1 = $this->getCoordinates($this->originAddress);
        $coords2 = $this->getCoordinates($address2);

        if ($coords1 && $coords2) {
            list($distance, $duration) = $this->getDistance($coords1, $coords2);
            return $distance; // retorna em km
        }

        return null;
    }

    public function getDistance($originCoords, $destinationCoords)
    {
        $origins = implode(',', $originCoords);
        $destinations = implode(',', $destinationCoords);

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json";
        $response = Http::get($url, [
            'origins' => $origins,
            'destinations' => $destinations,
            'key' => $this->googleApiKey,
        ]);

        $data = $response->json();

        if (!empty($data['rows'][0]['elements'][0]['distance']) && !empty($data['rows'][0]['elements'][0]['duration'])) {
            $distanceValue = $data['rows'][0]['elements'][0]['distance']['value'] / 1000; // km
            $durationText = $data['rows'][0]['elements'][0]['duration']['text'];
            return [$distanceValue, $durationText];
        }

        return [null, null];
    }

    public function calculateDeliveryFeeAmount($distance)
    {
        if ($distance <= 1) {
            return 3.00;
        } elseif ($distance <= 2) {
            return 5.00;
        } else {
            return 5.00 + ceil($distance - 2) * 1.00;
        }
    }
}
