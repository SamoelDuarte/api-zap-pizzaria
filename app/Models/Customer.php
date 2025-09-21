<?php
namespace App\Models;

use App\Services\DistanceService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $appends = [
        'phone',
        'location',
        'display_created_at',
    ];

    protected $fillable = [
        'name',
        'jid',
        'zipcode',
        'public_place',
        'neighborhood',
        'city',
        'complement',
        'state',
        'number',
        'tax',
        'created_at',
        'updated_at'
    ];
    protected DistanceService $distanceService;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->distanceService = new DistanceService();
    }

    public function setJidAttribute($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        if (!str_starts_with($value, '55')) {
            $value = '55' . $value;
        }
        $this->attributes['jid'] = $value ?: null;
    }

    public function getPhoneAttribute()
    {
        return substr($this->jid, 2);
    }

    public function getDisplayCreatedAtAttribute()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

    public function getLocationAttribute()
    {
        return 'CEP: ' . $this->zipcode . " " .
            $this->public_place . " " .
            'N° : ' . $this->number . " -- " .
            'Bairro: ' . $this->neighborhood . "\n" .
            'Cidade: ' . $this->city . " -- " .
            'Estado: ' . $this->state;
    }

    public function getAddressForMapsAttribute()
    {
        return "{$this->public_place} {$this->number}, {$this->city}, {$this->state}";
    }

    public function getLocationLink()
    {
        // Buscar endereço da pizzaria na configuração
        $config = \App\Models\Config::first();
        $pizzariaAddress = '';
        
        if ($config && $config->endereco) {
            $pizzariaAddress = "{$config->endereco}, {$config->numero}";
            if ($config->complemento) {
                $pizzariaAddress .= ", {$config->complemento}";
            }
            $pizzariaAddress .= ", {$config->bairro}, {$config->cidade}, {$config->estado}";
        } else {
            // Fallback para o endereço padrão
            $pizzariaAddress = 'Rua José Alves da Silva, 429, Parque Novo Santo Amaro, São Paulo, SP';
        }
        
        $origin = urlencode($pizzariaAddress);
        $destination = urlencode($this->address_for_maps);
        return "https://www.google.com/maps/dir/?api=1&origin={$origin}&destination={$destination}";
    }

    public function getDistanceInKilometers()
    {
        $address2 = $this->getAddressForMapsAttribute();
        return $this->distanceService->getDistanceInKm($address2);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}
