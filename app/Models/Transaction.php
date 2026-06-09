<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Mengizinkan kolom-kolom ini diisi ke database
    protected $fillable = [
        'item_id', // Menghubungkan ke ID tabel items
        'jenis',   // 'masuk' atau 'keluar'
        'jumlah',
    ];

    // Hubungan ke Model Item: Satu transaksi milik satu barang
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
