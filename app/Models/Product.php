<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image','description','price'];

    public function forms()
    {
        return $this->belongsToMany(Form::class, 'form_product')->withPivot('value');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}