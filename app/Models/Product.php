<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'brand_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function brand()
    {
        // modelo de la tabla padre (brand), [fk en la tabla hija (product), pk de la tabla padre (brand)]
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function tags()
    {
        // modelo de la segunda tabla, [nombre de la tabla intermedia, fk de la primera tabla, fk de la segunda tabla]
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id');
    }
}
