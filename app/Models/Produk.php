<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function Pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }

       
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class);
    }
}
