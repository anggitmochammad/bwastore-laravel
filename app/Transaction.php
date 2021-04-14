<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'users_id','insurance_price','shipping_price','transaction_status','total_price','code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

   //  relasi ke model user
   public function user(){
      // many to one 
      return $this->belongsTo(User::class, 'users_id','id');
   }

}
