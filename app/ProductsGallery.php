<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsGallery extends Model
{
    protected $fillable =[
        'products_id','photo'
    ];
    protected $hidden = [

    ];
    public function product()
    {
        // relasi belongs to ke model product menggunakan foreign key products_id
        // Many To One 
        return $this->belongsTo(Product::class, 'products_id','id');
    }
}
