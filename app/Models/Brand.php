<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function products()
    {
        // modelo de la tabla hija (product), [fk en la tabla hija (product), id de la tabla padre (brand)]
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
}
