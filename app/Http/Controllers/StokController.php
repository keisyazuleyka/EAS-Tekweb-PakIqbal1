<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        $items = Item::all();
        $transactions = Transaction::with('item')->latest()->get();
        return view('admin.stok', compact('items', 'transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        // 1. Simpan data ke tabel transaksi
        Transaction::create($request->all());

        // 2. Update jumlah stok di tabel items secara otomatis
        $item = Item::find($request->item_id);
        if ($request->jenis == 'masuk') {
            $item->increment('stok', $request->jumlah);
        } else {
            $item->decrement('stok', $request->jumlah);
        }

        return redirect()->back()->with('success', 'Transaksi stok berhasil diperbarui!');
    }
}
