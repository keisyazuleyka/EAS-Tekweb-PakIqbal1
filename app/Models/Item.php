<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Tambahkan 'kategori' di sini!
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori', // <--- INI WAJIB ADA
        'stok'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'item_id');
    }
}
