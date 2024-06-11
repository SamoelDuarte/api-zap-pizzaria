<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $table = 'config';
    protected $fillable = [
        'motoboy_fone',
        'status',
        'chatbot',
        'resposta',
    ];

    protected $casts = [
        'status' => 'boolean',
        'chatbot' => 'boolean',
    ];
}