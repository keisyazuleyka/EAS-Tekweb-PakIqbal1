<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminController extends Controller
{
    // 1. Khusus Dashboard Super Admin
    public function superAdminDashboard()
    {
        $hasItems = Schema::hasTable('items');
        $hasTransactions = Schema::hasTable('transactions');

        // Label bulan disingkat agar lebih rapi di grafik
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataTransaksiBulan = [];

        if ($hasTransactions) {
            for ($i = 1; $i <= 12; $i++) {
                // MENGGUNAKAN sum('jumlah') AGAR MENGHITUNG VOLUME TRANSAKSI BARANG
                $jumlah = Transaction::whereMonth('created_at', $i)
                                     ->whereYear('created_at', date('Y'))
                                     ->sum('jumlah');
                $dataTransaksiBulan[] = $jumlah;
            }
        } else {
            $dataTransaksiBulan = array_fill(0, 12, 0);
        }

        $data = [
            'users' => User::where('role', '!=', 'superadmin')->get(),
            'pending_users' => User::where('is_approved', false)->get(),
            'total_users' => User::count(),
            'total_items' => $hasItems ? Item::count() : 0,
            'total_stock' => $hasItems ? Item::sum('stok') : 0,
            'total_transaksi' => $hasTransactions ? Transaction::count() : 0,
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
            'totalBarang'   => Schema::hasTable('items') ? Item::count() : 0,
            'countMenipis'  => Schema::hasTable('items') ? Item::where('stok', '<', 5)->count() : 0,
            'totalMasuk'    => Schema::hasTable('transactions') ? Transaction::where('jenis', 'masuk')->sum('jumlah') : 0,
            'totalKeluar'   => Schema::hasTable('transactions') ? Transaction::where('jenis', 'keluar')->sum('jumlah') : 0,
            'aktivitas'     => Schema::hasTable('transactions') ? Transaction::latest()->take(5)->get() : collect(),
            'countAman'     => Schema::hasTable('items') ? Item::where('stok', '>=', 5)->count() : 0,
            'countHabis'    => Schema::hasTable('items') ? Item::where('stok', 0)->count() : 0,
        ];

        $labels = [];
        $dataMasukChart = [];
        $dataKeluarChart = [];

        if (Schema::hasTable('transactions')) {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $labels[] = $date->format('d M');

                $dataMasukChart[] = Transaction::whereDate('created_at', $date->toDateString())
                                               ->where('jenis', 'masuk')
                                               ->sum('jumlah');

                $dataKeluarChart[] = Transaction::whereDate('created_at', $date->toDateString())
                                                ->where('jenis', 'keluar')
                                                ->sum('jumlah');
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
