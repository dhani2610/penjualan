<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{

    use HasFactory;
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
