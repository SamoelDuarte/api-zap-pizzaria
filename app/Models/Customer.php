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
        'delivery_fee',
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
        $origin = urlencode('Rua José Alves da Silva, 429, Parque Novo Santo Amaro, São Paulo, SP');
        $destination = urlencode($this->address_for_maps);
        return "https://www.google.com/maps/dir/?api=1&origin={$origin}&destination={$destination}";
    }

    public function getDeliveryFeeAttribute()
    {
        $address2 = $this->getAddressForMapsAttribute();

        $distance = $this->distanceService->getDistanceInKm($address2);
        if ($distance !== null) {
            return $this->distanceService->calculateDeliveryFeeAmount($distance);
        }

        return null;
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
