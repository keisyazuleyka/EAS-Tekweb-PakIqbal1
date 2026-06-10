<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogisTix - Laporan</title>
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

        /* Spesifik Laporan */
        .laporan-card { background-color: #e5e7eb; border-radius: 8px; padding: 20px; border: 1px solid #d1d5db; position: relative; }
        .laporan-card h6 { font-weight: 700; color: #374151; font-size: 1rem; margin-bottom: 10px; }
        .laporan-card h2 { font-weight: 800; color: #111827; font-size: 2.5rem; margin: 0; }
        .laporan-card .icon-top { position: absolute; top: 20px; right: 20px; opacity: 0.7; }

        .laporan-table-wrapper { border: 1px solid #d1d5db; border-radius: 8px; overflow: hidden; background: white; }
        .laporan-table-header { background-color: #d1d5db; padding: 15px 20px; font-size: 1.25rem; font-weight: 700; color: #374151; }
        .table-custom thead th { background-color: #e5e7eb; color: #374151; font-weight: 600; border-bottom: 2px solid #d1d5db; padding: 12px 15px; }
        .table-custom tbody td { padding: 12px 15px; vertical-align: middle; color: #4b5563; }

        /* Status Pill */
        .status-pill { display: inline-block; width: 60px; height: 20px; border-radius: 50px; }
        .status-hijau { background-color: #4ade80; }
        .status-merah { background-color: #f87171; }
    </style>
</head>

<body class="d-flex">

    <div class="sidebar">
        <div class="sidebar-brand">Logis<span>Tix</span></div>
        <div class="d-flex flex-column w-100">
            <div class="nav-item {{ request()->routeIs('dashboard') ? 'active-menu' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> Dashboard
                </a>
            </div>
            <div class="nav-item {{ request()->routeIs('barang.index') ? 'active-menu' : '' }}">
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

    <div class="content-area p-5 bg-white">
        <form action="{{ route('laporan.index') }}" method="GET" class="d-flex gap-3 mb-4 align-items-end">
            <div style="width: 200px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-secondary text-secondary">Tgl Mulai</span>
                    <input type="date" name="start_date" class="form-control border-secondary bg-light" value="{{ request('start_date') }}">
                </div>
            </div>
            <div style="width: 200px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-secondary text-secondary">Tgl Selesai</span>
                    <input type="date" name="end_date" class="form-control border-secondary bg-light" value="{{ request('end_date') }}">
                </div>
            </div>
            <button type="submit" class="btn text-white fw-semibold" style="background-color: #3b5bdb; border-radius: 6px; padding: 8px 25px;">Filter</button>
            @if(request('start_date') || request('end_date'))
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </form>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="laporan-card">
                    <h6>Total Barang Masuk</h6>
                    <h2>{{ number_format($totalMasuk, 0, ',', '.') }}</h2>
                    <div class="icon-top">↘️</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="laporan-card">
                    <h6>Total Barang Keluar</h6>
                    <h2>{{ number_format($totalKeluar, 0, ',', '.') }}</h2>
                    <div class="icon-top">↗️</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="laporan-card">
                    <h6>Stok Tersisa</h6>
                    <h2>{{ number_format($stokTersisa, 0, ',', '.') }}</h2>
                    <div class="icon-top">📦</div>
                </div>
            </div>
        </div>

        <div class="laporan-table-wrapper">
            <div class="laporan-table-header">Laporan Report Table</div>
            <div class="table-responsive">
                <table class="table table-hover table-custom mb-0 text-center">
                    <thead>
                        <tr>
                            <th>[ Tanggal ]</th>
                            <th class="text-start">[ Nama Barang ]</th>
                            <th>[ Kategori ]</th>
                            <th>[ Masuk ]</th>
                            <th>[ Keluar ]</th>
                            <th>[ Status ]</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                        <tr>
                            <td>{{ $t->created_at->format('d/m/Y') }}</td>
                            <td class="text-start fw-medium">{{ $t->item->nama_barang ?? 'Barang Terhapus' }}</td>
                            <td>{{ $t->item->kategori ?? '-' }}</td>
                            @if($t->jenis == 'masuk')
                                <td class="fw-bold">{{ $t->jumlah }}</td>
                                <td>0</td>
                                <td><span class="status-pill status-hijau"></span></td>
                            @else
                                <td>0</td>
                                <td class="fw-bold">{{ $t->jumlah }}</td>
                                <td><span class="status-pill status-merah"></span></td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada data laporan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
