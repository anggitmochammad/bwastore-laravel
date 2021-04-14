<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'transactions_id','products_id','shipping_status','resi','code','price'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
     public function product()
    {
        // One To One 
        return $this->hasOne(Product::class,'id','products_id');
    }
     public function transaction()
    {
        // One To One 
        return $this->hasOne(Transaction::class,'id','transactions_id');
    }
}
