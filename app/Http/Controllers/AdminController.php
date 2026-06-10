<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    // 1. Khusus Dashboard Super Admin
    public function superAdminDashboard()
    {
        // Label bulan disingkat agar lebih rapi di grafik
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        // Inisialisasi array dengan nilai 0 untuk 12 bulan
        $dataTransaksiBulan = array_fill(0, 12, 0);

        // OPTIMASI: Mengambil total transaksi per bulan dalam 1x Query (Disesuaikan untuk SQLite)
        $monthlyTotals = Transaction::selectRaw("strftime('%m', created_at) as month, SUM(jumlah) as total")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        // Memasukkan data ke dalam array sesuai bulannya
        foreach ($monthlyTotals as $month => $total) {
            // Konversi ke (int) agar angka bulan seperti "06" terbaca sebagai 6
            $dataTransaksiBulan[(int)$month - 1] = $total;
        }

        $data = [
            'users' => User::where('role', '!=', 'superadmin')->get(),
            'pending_users' => User::where('is_approved', false)->get(),
            'total_users' => User::count(),
            'total_items' => Item::count(),
            'total_stock' => Item::sum('stok'),
            'total_transaksi' => Transaction::count(),
            'admin_aktif' => User::where('role', 'admin')->where('is_approved', true)->count(),
            'bulanLabels' => $bulanLabels,
            'dataTransaksiBulan' => $dataTransaksiBulan
        ];

        return view('admin.dashboard', $data);
    }

    // 2. Khusus Dashboard Admin Biasa
    public function dashboardTeman()
    {
        $data = [
            'totalBarang'   => Item::count(),
            'totalStok'     => Item::sum('stok'), // Ditambahkan untuk card total stok
            'countMenipis'  => Item::where('stok', '<', 5)->count(),
            'totalMasuk'    => Transaction::where('jenis', 'masuk')->sum('jumlah'),
            'totalKeluar'   => Transaction::where('jenis', 'keluar')->sum('jumlah'),
            'aktivitas'     => Transaction::with('item')->latest()->take(5)->get(), // Ditambahkan with('item') agar relasi nama barang terbaca
            'countAman'     => Item::where('stok', '>=', 5)->count(),
            'countHabis'    => Item::where('stok', 0)->count(),
        ];

        $labels = [];
        $dataMasukChart = array_fill(0, 7, 0);
        $dataKeluarChart = array_fill(0, 7, 0);
        $dateMapping = [];

        // Siapkan label 7 hari ke belakang beserta index-nya
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            $dateMapping[$date->toDateString()] = 6 - $i; // Simpan urutan index array
        }

        $startDate = Carbon::today()->subDays(6)->toDateString();

        // OPTIMASI: Mengambil rekap transaksi 7 hari terakhir masuk/keluar dalam 1x Query
        $weeklyTransactions = Transaction::selectRaw('DATE(created_at) as date, jenis, SUM(jumlah) as total')
            ->whereDate('created_at', '>=', $startDate)
            ->groupBy('date', 'jenis')
            ->get();

        // Memetakan hasil query ke chart array berdasarkan tanggal
        foreach ($weeklyTransactions as $transaction) {
            $dateKey = Carbon::parse($transaction->date)->toDateString();

            if (isset($dateMapping[$dateKey])) {
                $index = $dateMapping[$dateKey];

                if ($transaction->jenis === 'masuk') {
                    $dataMasukChart[$index] = $transaction->total;
                } elseif ($transaction->jenis === 'keluar') {
                    $dataKeluarChart[$index] = $transaction->total;
                }
            }
        }

        $data['labels'] = $labels;
        $data['dataMasukChart'] = $dataMasukChart;
        $data['dataKeluarChart'] = $dataKeluarChart;

        return view('admin.admdash', $data);
    }

    public function updateJabatan(Request $request, $id) {
        $request->validate(['jabatan' => 'required|in:superadmin,admin']);
        $user = User::findOrFail($id);
        $user->jabatan = $request->jabatan;
        $user->role = $request->jabatan;
        $user->save();
        return back()->with('success', 'Jabatan diperbarui.');
    }

    public function toggle($id) {
        $user = User::findOrFail($id);
        if ($user->role === 'superadmin') return back();
        $user->is_approved = !$user->is_approved;
        $user->save();
        return back();
    }

    public function delete($id) {
        $user = User::findOrFail($id);
        if ($user->role === 'superadmin') return back();
        $user->delete();
        return back();
    }

    public function role($id) {
        $user = User::findOrFail($id);
        if ($user->role === 'superadmin') return back();
        $user->role = ($user->role === 'admin') ? 'staf' : 'admin';
        $user->save();
        return back();
    }
}
