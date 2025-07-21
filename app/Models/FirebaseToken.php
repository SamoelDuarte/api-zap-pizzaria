<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirebaseToken extends Model
{
    use HasFactory;

    protected $fillable = ['token'];
    protected $table = 'firebase_tokens'; // garante o nome da tabela
}
