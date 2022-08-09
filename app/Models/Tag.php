<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function products()
    {
        // modelo de la segunda tabla, [nombre de la tabla intermedia, fk de la primera tabla, fk de la segunda tabla]
        return $this->belongsToMany(Product::class, 'product_tag', 'tag_id', 'product_id');
    }
}
