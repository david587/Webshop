<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Order;
use app\Models\cartItem;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "price",
        "details",
        "image",
        "inStock",
        "brand_id",
        "categorie_id"
    ];

    public $timestamps = false;

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function order(){
        return $this->hasMany(Order::class);
    }

    public function cartItem(){
        return $this->hasMany(cartItem::class);
    }
}
