<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Siapkan kerangka query untuk transaksi
        $query = Transaction::with('item')->latest();

        // 2. Cek apakah user menekan tombol Filter Tanggal
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // 3. Ambil datanya
        $transactions = $query->get();

        // 4. Hitung angka untuk kartu ringkasan (Card)
        $totalMasuk = $transactions->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = $transactions->where('jenis', 'keluar')->sum('jumlah');

        // Stok tersisa adalah total semua stok barang saat ini di gudang
        $stokTersisa = Item::sum('stok');

        return view('admin.laporan', compact('transactions', 'totalMasuk', 'totalKeluar', 'stokTersisa'));
    }

    // Fungsi untuk export/download ke CSV
    public function export($type)
    {
        $filename = "Laporan_" . $type . "_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');
            // Header CSV
            fputcsv($file, ['Tanggal', 'Nama Barang', 'Kategori', 'Jenis', 'Jumlah', 'Keterangan']);

            $query = Transaction::with('item');

            // Filter berdasarkan jenis jika diminta
            if ($type == 'masuk') {
                $query->where('jenis', 'masuk');
            } elseif ($type == 'keluar') {
                $query->where('jenis', 'keluar');
            }

            foreach ($query->get() as $row) {
                fputcsv($file, [
                    $row->created_at->format('d/m/Y'),
                    $row->item->nama_barang ?? 'Barang Terhapus',
                    $row->item->kategori ?? '-',
                    $row->jenis,
                    $row->jumlah,
                    $row->keterangan ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
