<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Http\Controllers\Controller; // Jangan lupa tambahkan ini

class AdminBiasaController extends Controller
{
    public function index()
    {
        $data = [
            'totalBarang'   => Item::count(),
            'countMenipis'  => Item::where('stok', '<', 5)->count(),
            'totalMasuk'    => Transaction::where('jenis', 'masuk')->sum('jumlah'),
            'totalKeluar'   => Transaction::where('jenis', 'keluar')->sum('jumlah'),
            'aktivitas'     => Transaction::latest()->take(5)->get(),
        ];

        // Pastikan nama view sesuai, misalnya 'admin.admdash'
        return view('admin.admdash', $data);
    }
}
