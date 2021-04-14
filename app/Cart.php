<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'products_id', 'users_id'
    ];
    protected $hidden = [
        
    ];
    // membuat relasi ke model product
    public function product(){
        // one to one 
      return $this->hasOne(Product::class, 'id','products_id');  
    } 
    public function user(){
        // Many To One 
        return $this->belongsTo(User::class, 'users_id','id');
    }
     
}
