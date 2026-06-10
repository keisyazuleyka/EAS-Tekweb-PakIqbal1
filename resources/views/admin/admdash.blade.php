<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogisTix - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; margin: 0; padding: 0; overflow-x: hidden; }
        .sidebar { background-color: #111152; width: 240px; flex-shrink: 0; display: flex; flex-direction: column; padding-top: 35px; min-height: 100vh; font-family: 'Inter', sans-serif; }
        .sidebar-brand { font-size: 2.1rem; font-weight: 800; color: #ffffff; padding-left: 24px; margin-bottom: 55px; letter-spacing: -1px; }
        .sidebar-brand span { color: #f36f21; }
        .sidebar .nav-item { width: 100%; }
        .sidebar .nav-link { color: #727ba2; font-weight: 500; padding: 12px 24px; display: flex; align-items: center; gap: 12px; font-size: 0.95rem; text-decoration: none; transition: color 0.2s; }
        .sidebar .nav-link:hover { color: #ffffff; }
        .sidebar .nav-item.active-menu { background-color: #3e1f38; border-left: 4px solid #f36f21; }
        .sidebar .nav-item.active-menu .nav-link { color: #ffffff; }
        .sidebar .nav-link svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 1.5; opacity: 0.6; }
        .sidebar .nav-item.active-menu .nav-link svg { opacity: 1; }
        .content-area { flex-grow: 1; }
        .top-navbar { background-color: #111152; height: 60px; color: white; display: flex; align-items: center; justify-content: flex-end; padding-right: 20px; }
        .stat-card { background-color: #e2e2e2; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); height: 100%; }
        .stat-title { font-size: 14px; color: #888; font-weight: 500; text-transform: uppercase; margin-bottom: 10px; }
        .stat-value { font-size: 42px; font-weight: 600; color: #222; }
        .stock-status-item { margin-bottom: 10px; }
        .stock-status-label { font-size: 14px; color: #4a4a4a; font-weight: 500; }
        .stock-status-number { font-size: 14px; color: #222; font-weight: 600; }
        .table thead th { font-size: 14px; color: #a1a1a1; font-weight: 500; }
    </style>
</head>
<body class="d-flex">

    <div class="sidebar">
        <div class="sidebar-brand">Logis<span>Tix</span></div>

        <div class="d-flex flex-column w-100">
            <div class="nav-item active-menu">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('barang.index') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg> Barang
                </a>
            </div>
            <div class="nav-item {{ request()->routeIs('stok.index') ? 'active-menu' : '' }}">
                <a href="{{ route('stok.index') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg> Stok
                </a>
            </div>
            <div class="nav-item {{ request()->routeIs('laporan.index') ? 'active-menu' : '' }}">
                <a href="{{ route('laporan.index') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg> Laporan
                </a>
            </div>
            <hr class="text-secondary mx-3 mt-4 mb-3">
            <form method="POST" action="{{ route('logout') }}" class="px-3">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100" style="font-size: 0.85rem;">Logout</button>
            </form>
        </div>
    </div>

    <div class="content-area">
        <div class="top-navbar">
            <small style="color: #ffffff; font-weight: 500; letter-spacing: 0.5px;">{{ now()->format('l, d M Y') }} <span style="color: #f36f21;">●</span></small>
        </div>

        <div class="p-4">
            <div class="row mb-4">
                <div class="col-md-3"><div class="stat-card"><div class="stat-title">TOTAL BARANG</div><div class="stat-value">{{ $totalBarang ?? 0 }}</div></div></div>
                <div class="col-md-3"><div class="stat-card"><div class="stat-title">MENIPIS</div><div class="stat-value">{{ $countMenipis ?? 0 }}</div></div></div>
                <div class="col-md-3"><div class="stat-card"><div class="stat-title">MASUK</div><div class="stat-value">{{ $totalMasuk ?? 0 }}</div></div></div>
                <div class="col-md-3"><div class="stat-card"><div class="stat-title">KELUAR</div><div class="stat-value">{{ $totalKeluar ?? 0 }}</div></div></div>
            </div>

            @php
                $safeTotal = ($totalBarang ?? 0) > 0 ? $totalBarang : 1;
                $pctAman = (($countAman ?? 0) / $safeTotal) * 100;
                $pctMenipis = (($countMenipis ?? 0) / $safeTotal) * 100;
                $pctHabis = (($countHabis ?? 0) / $safeTotal) * 100;
            @endphp

            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h6 class="fw-bold mb-3">Pergerakan Stok Mingguan</h6>
                        <div class="d-flex justify-content-around align-items-end" style="height: 160px; background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px; padding: 10px 20px;">
                            @if(isset($labels) && isset($dataMasukChart) && isset($dataKeluarChart))
                                @php
                                    $maxVal = max(max($dataMasukChart ?: [0]), max($dataKeluarChart ?: [0]));
                                    $maxVal = $maxVal > 0 ? $maxVal : 1;
                                @endphp
                                @foreach($labels as $index => $label)
                                    @php
                                        $hMasuk = ($dataMasukChart[$index] / $maxVal) * 110;
                                        $hKeluar = ($dataKeluarChart[$index] / $maxVal) * 110;
                                    @endphp
                                    <div class="chart-bars-pair d-flex flex-column align-items-center gap-2">
                                        <div class="d-flex align-items-end gap-2" style="height: 110px;">
                                            <div style="background-color: #0b0e4f; width: 20px; height: {{ $hMasuk }}px; border-radius: 2px;" title="Masuk: {{ $dataMasukChart[$index] }}"></div>
                                            <div style="background-color: #ff0000; width: 20px; height: {{ $hKeluar }}px; border-radius: 2px;" title="Keluar: {{ $dataKeluarChart[$index] }}"></div>
                                        </div>
                                        <small style="font-size: 11px; color: #a1a1a1; font-weight: 500;">{{ $label }}</small>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 h-100" style="background-color: #e2e2e2;">
                        <h6 class="fw-bold mb-3">Status Stok</h6>
                        <div class="stock-status-item d-flex justify-content-between align-items-center mb-1">
                            <div class="stock-status-label">Aman</div><div class="stock-status-number">{{ $countAman ?? 0 }}</div>
                        </div>
                        <div class="progress mb-3" style="height: 6px; background-color: #d1d1d1;"><div class="progress-bar" style="width: {{ $pctAman }}%; background-color: #61d836;"></div></div>
                        <div class="stock-status-item d-flex justify-content-between align-items-center mb-1">
                            <div class="stock-status-label">Menipis</div><div class="stock-status-number">{{ $countMenipis ?? 0 }}</div>
                        </div>
                        <div class="progress mb-3" style="height: 6px; background-color: #d1d1d1;"><div class="progress-bar" style="width: {{ $pctMenipis }}%; background-color: #ffb800;"></div></div>
                        <div class="stock-status-item d-flex justify-content-between align-items-center mb-1">
                            <div class="stock-status-label">Habis</div><div class="stock-status-number">{{ $countHabis ?? 0 }}</div>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #d1d1d1;"><div class="progress-bar" style="width: {{ $pctHabis }}%; background-color: #cc4949;"></div></div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0"><h6 class="fw-bold">Aktivitas Terbaru</h6></div>
                <div class="card-body">
                    <table class="table table-borderless table-hover text-center">
                        <thead class="text-muted border-bottom"><tr><th>Waktu</th><th>Kode</th><th>Barang</th><th>Jenis</th><th>Jumlah</th></tr></thead>
                        <tbody>
                            @forelse($aktivitas ?? [] as $a)
                            <tr>
                                <td>{{ $a->created_at->format('H:i') }}</td>
                                <td>{{ $a->item->kode_barang ?? '-' }}</td>
                                <td>{{ $a->item->nama_barang ?? '-' }}</td>
                                <td><span class="badge {{ $a->jenis == 'masuk' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($a->jenis) }}</span></td>
                                <td>{{ $a->jumlah }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada aktivitas transaksi terbaru.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
