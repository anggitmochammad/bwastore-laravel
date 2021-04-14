<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'users_id','categories_id','price','description','slug'
    ];
    protected $hidden = [

    ];

    // Untuk Relasi
    public function galleries()
    {
        // one to many / 1 product bisa memiliki banyak gallery
        return $this->hasMany(ProductsGallery::class,'products_id','id');
    }
    public function user()
    {
        // one to one / 1 produk hanya dimiliki satu user
        return $this->hasOne(User::class,'id','users_id');
    }
    public function category()
    {
        // Many To One / banyak Product hanya dimiliki 1 Category
        return $this->belongsTo(Category::class,'categories_id','id');
    }

}
