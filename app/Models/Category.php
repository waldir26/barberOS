<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBarbershop;

class Category extends Model
{
    use BelongsToBarbershop;

    protected $fillable = ['barbershop_id', 'nombre'];


    public function products()
    {
        return $this->hasMany(Product::class, 'categoria_id');
    }
}
