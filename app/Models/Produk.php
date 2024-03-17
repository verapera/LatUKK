<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'produks';
    protected $primaryKey = 'produk_id';
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'harga',
        'stok',
    ];
    public static function kodeproduk(){
        return 'A'.date('ymd').str_pad(Produk::withTrashed()->count()+1,1,'0',STR_PAD_LEFT); 
    }
}
