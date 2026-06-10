<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LogisTix - Super Head Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#ED0000',
                        navy: '#1a1c3a'
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#F8FAFC] font-sans antialiased text-gray-800 selection:bg-brand selection:text-white">

<nav class="bg-navy text-white px-8 py-4 shadow-lg flex justify-between items-center sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <h1 class="text-2xl font-extrabold tracking-tight"><span class="text-white">Logis</span><span class="text-[#f36f21]">Tix</span></h1>
    </div>

    <div class="flex items-center gap-6">
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-300 hover:text-white transition">← Mode Staf</a>
        <div class="h-6 w-px bg-gray-600"></div>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="text-sm bg-brand hover:bg-red-700 px-5 py-2 rounded-lg font-bold transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                Keluar Sistem
            </button>
        </form>
    </div>
</nav>

    <div class="max-w-7xl mx-auto py-10 px-6 lg:px-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
            <div>
                <p class="text-sm font-semibold text-brand tracking-wider uppercase mb-1">Dashboard Eksekutif</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Welcome back, {{ Auth::user()->first_name ?? 'Admin' }}!
                </h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <span class="text-2xl">🔔</span>
                    @if(isset($pending_users) && $pending_users->count() > 0)
                        <span class="absolute -top-1 -right-1 bg-brand text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full border-2 border-white animate-bounce">
                            {{ $pending_users->count() }}
                        </span>
                    @endif
                </div>
                <span class="bg-red-100 text-brand text-xs font-black px-4 py-2 rounded-full uppercase tracking-widest border border-red-200 shadow-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-brand animate-pulse"></span>
                    Super Head Admin
                </span>
            </div>
        </div>

        @if(isset($pending_users) && $pending_users->count() > 0)
        <div class="mb-10 animate-pulse">
            <div class="bg-red-50 border-l-8 border-brand p-6 rounded-2xl shadow-md border border-red-100">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-brand font-black uppercase tracking-tighter text-lg flex items-center gap-2">
                            ⚠️ Perhatian: Ada {{ $pending_users->count() }} Pendaftar Baru!
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">Sistem mendeteksi permintaan registrasi staf gudang yang membutuhkan persetujuan.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($pending_users as $pending)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white p-4 rounded-xl shadow-sm border border-red-100 gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center font-bold text-brand text-xl">
                                {{ substr($pending->first_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-base">{{ $pending->first_name }} {{ $pending->last_name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">📞 {{ $pending->phone }} | 📧 {{ $pending->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.approve', $pending->id) }}" class="w-full sm:w-auto bg-brand hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg font-bold text-xs tracking-wider transition shadow-md block text-center">
                            ✅ ACC SEKARANG
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-5 gap-5 mb-10">
            @php
                $stats = [
                    ['title' => 'Total User', 'value' => $total_users ?? 0, 'icon' => '👥'],
                    ['title' => 'Barang', 'value' => $total_items ?? 0, 'icon' => '📦'],
                    ['title' => 'Total Stok', 'value' => $total_stock ?? 0, 'icon' => '🏢'],
                    ['title' => 'Transaksi', 'value' => $total_transaksi ?? 0, 'icon' => '🔄'],
                    ['title' => 'Admin Aktif', 'value' => $admin_aktif ?? 0, 'icon' => '🛡️'],
                ];
            @endphp

            @foreach($stats as $stat)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-red-100 transition duration-300 group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gray-100 group-hover:bg-brand transition-colors duration-300"></div>
                <div class="flex justify-between items-start mb-4">
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">{{ $stat['title'] }}</p>
                    <span class="text-xl opacity-80">{{ $stat['icon'] }}</span>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $stat['value'] }}</h3>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="font-bold text-gray-900 text-lg">Aktivitas Sistem Terkini</h2>
                        <p class="text-xs text-gray-500 mt-1">Pemantauan transaksi logistik per bulan</p>
                    </div>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="font-bold text-gray-900 text-lg mb-1">Pusat Laporan</h2>
                    <p class="text-xs text-gray-500 mb-6">Unduh dokumen administrasi</p>

                    <div class="space-y-4">
                        <a href="{{ route('laporan.export', 'stok') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-red-50 hover:border-brand border border-transparent transition duration-200 group">
                            <span class="text-sm font-bold text-gray-700 group-hover:text-brand transition">📊 Laporan Stok Gudang</span>⬇️
                        </a>
                        <a href="{{ route('laporan.export', 'masuk') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-red-50 hover:border-brand border border-transparent transition duration-200 group">
                            <span class="text-sm font-bold text-gray-700 group-hover:text-brand transition">📥 Riwayat Barang Masuk</span>⬇️
                        </a>
                        <a href="{{ route('laporan.export', 'keluar') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-red-50 hover:border-brand border border-transparent transition duration-200 group">
                            <span class="text-sm font-bold text-gray-700 group-hover:text-brand transition">📤 Riwayat Barang Keluar</span>⬇️
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-10">
            <div class="p-8 border-b border-gray-100 bg-white flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-gray-900 text-lg">Manajemen Pengguna (Aktif & Blokir)</h2>
                    <p class="text-xs text-gray-500 mt-1">Kelola jabatan dan akses sistem karyawan</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-[#F8FAFC] text-gray-500 text-[11px] uppercase tracking-widest border-b border-gray-100">
                        <tr>
                            <th class="px-8 py-5 font-bold">Informasi Akun</th>
                            <th class="px-8 py-5 font-bold">Jabatan</th>
                            <th class="px-8 py-5 text-center font-bold">Status Akses</th>
                            <th class="px-8 py-5 text-right font-bold">Tindakan Sistem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if(isset($users) && count($users) > 0)
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-brand font-bold">
                                            {{ substr($user->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</div>
                                            <div class="text-[11px] text-gray-500 font-medium">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-gray-200">
                                        {{ strtoupper($user->jabatan ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black tracking-wider border {{ $user->is_approved ? 'bg-green-50 text-green-600 border-green-200' : 'bg-red-50 text-brand border-red-200' }}">
                                        {{ $user->is_approved ? 'AKTIF' : 'DIBLOKIR' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 flex justify-end gap-2 items-center h-full">
                                    <form action="{{ route('admin.user.toggle', $user->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-white text-gray-600 border border-gray-200 px-4 py-2 rounded-lg text-[10px] font-bold hover:bg-gray-100 hover:text-gray-900 transition shadow-sm">
                                            {{ $user->is_approved ? 'BLOKIR' : 'BUKA AKSES' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" onsubmit="return confirm('Tindakan ini tidak bisa dibatalkan. Lanjutkan?')">
                                        @csrf @method('DELETE')
                                        <button class="bg-white text-brand border border-red-200 px-4 py-2 rounded-lg text-[10px] font-bold hover:bg-red-50 transition shadow-sm">
                                            HAPUS
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-gray-500">Belum ada akun yang terdaftar.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('mainChart').getContext('2d');
            let gradient = ctx.createLinearGradient(0, 0, 0, 350);
            gradient.addColorStop(0, 'rgba(237, 0, 0, 0.25)');
            gradient.addColorStop(1, 'rgba(237, 0, 0, 0.0)');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($bulanLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']) !!},
                    datasets: [{
                        data: {!! json_encode($dataTransaksiBulan ?? [0,0,0,0,0,0,0,0,0,0,0,0]) !!},
                        borderColor: '#ED0000',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            setInterval(function() {
                window.location.reload();
            }, 5000);
        });
    </script>
</body>
</html>
