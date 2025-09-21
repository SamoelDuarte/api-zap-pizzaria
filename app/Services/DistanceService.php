<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DistanceService
{
    protected $graphhopperUrl = 'http://147.79.111.119:8989';

    public function getCoordinates($address)
    {
        $url = 'https://nominatim.openstreetmap.org/search';

        $response = Http::accept('application/json')
            ->withHeaders([
                'User-Agent' => 'SeuApp/1.0 (seuemail@exemplo.com)', // obrigatório pelo Nominatim
            ])
            ->get($url, [
                'q' => $address,
                'format' => 'json',
                'limit' => 1,
            ]);

        $data = $response->json();

        if (is_array($data) && count($data) > 0 && isset($data[0]['lat'], $data[0]['lon'])) {
            return [(float)$data[0]['lat'], (float)$data[0]['lon']];
        }

        return null;
    }



    // Função que faz a chamada para sua API GraphHopper
    public function getDistanceInKm($address2)
    {
        // Buscar endereço da pizzaria na configuração
        $config = \App\Models\Config::first();
        $address1 = '';
        
        if ($config && $config->endereco) {
            $address1 = "{$config->endereco}, {$config->numero}";
           
            $address1 .= ", {$config->bairro}, {$config->cidade}, {$config->estado}";
        } else {
            // Fallback para o endereço padrão
            $address1 = 'Rua José Alves da Silva, 429, São Paulo, SP';
        }
        
        $coords1 = $this->getCoordinates($address1);
        $coords2 = $this->getCoordinates($address2);
        if ($coords1 && $coords2) {
            return $this->getDistance($coords1, $coords2);
        }

        return null;
    }

    // Chamada à API GraphHopper para calcular rota e extrair distância
    public function getDistance(array $originCoords, array $destinationCoords)
    {
        // Monta os parâmetros point=lat,lon
        $points = [
            'point' => [
                $originCoords[0] . ',' . $originCoords[1],
                $destinationCoords[0] . ',' . $destinationCoords[1],
            ],
            'profile' => 'car',
            'locale' => 'pt',
            'calc_points' => 'false',  // não precisa dos pontos da rota
            'instructions' => 'false', // sem instruções, só distância
        ];

        // Como são múltiplos parâmetros "point", precisamos montar a query manualmente
        $query = http_build_query([
            'profile' => $points['profile'],
            'locale' => $points['locale'],
            'calc_points' => $points['calc_points'],
            'instructions' => $points['instructions'],
        ]);
        $query .= '&point=' . urlencode($points['point'][0]);
        $query .= '&point=' . urlencode($points['point'][1]);

        $url = $this->graphhopperUrl . '/route?' . $query;

        $response = Http::get($url);

        $data = $response->json();

        // Verifica se retornou a rota e extrai distância (em metros)
        if (!empty($data['paths'][0]['distance'])) {
            $distanceMeters = $data['paths'][0]['distance'];
            $distanceKm = $distanceMeters / 1000;
            return $distanceKm;
        }

        return null;
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
