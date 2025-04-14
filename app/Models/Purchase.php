<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Purchase extends Model
{
    use HasFactory;
    protected $table = 'purchases';
    protected $fillable = ['receipt_code','user_id', 'customer_id', 'used_points' , 'total_price', 'total_payment', 'change']; 

     public function purchaseProducts()
     {
        return $this->hasMany(PurchaseProduct::class);
     }

     public function user()
     {
        return $this->belongsTo(User::class);
     }

     public function customer()
     {
        return $this->belongsTo(Customer::class);
     }
}
