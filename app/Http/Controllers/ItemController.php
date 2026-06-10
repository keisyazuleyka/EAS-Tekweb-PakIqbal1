<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Menampilkan daftar barang
    public function index()
    {
        $items = Item::all();

        return view('admin.barang.barang', compact('items'));
    }

    // Menyimpan barang baru
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|in:Elektronik,Fashion,Meubel,ATK,Aksesoris,Makeup',
            'stok'        => 'required|integer|min:0',
        ]);

        // 2. Logika kode otomatis (BRG001, BRG002, dst)
        // Diurutkan berdasarkan 'id' secara menurun untuk memastikan mengambil data terakhir yang di-insert
        $lastItem = Item::orderBy('id', 'desc')->first();

        // Ambil 3 karakter pertama (BRG) lalu sisanya diubah ke integer, tambah 1
        $nextId = $lastItem ? (int) substr($lastItem->kode_barang, 3) + 1 : 1;
        $kodeOtomatis = 'BRG' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // 3. Simpan ke Database
        Item::create([
            'kode_barang' => $kodeOtomatis,
            'nama_barang' => $request->nama_barang,
            'kategori'    => $request->kategori,
            'stok'        => $request->stok,
        ]);

        // 4. Redirect agar data langsung muncul di daftar
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // Menghapus barang
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
