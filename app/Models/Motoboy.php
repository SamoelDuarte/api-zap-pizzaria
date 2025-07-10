<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motoboy extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Mutator para sempre salvar o telefone com "55" no início
    public function setPhoneAttribute($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (!str_starts_with($value, '55')) {
            $value = '55' . $value;
        }

        $this->attributes['phone'] = $value;
    }
}
