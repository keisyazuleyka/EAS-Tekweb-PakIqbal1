<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogisTix - Kelola Stok</title>
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
        .card-custom { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .btn-merah { background-color: #ef3b4e; color: white; font-weight: 600; border: none; padding: 10px 30px; border-radius: 6px; transition: background 0.2s; }
        .btn-merah:hover { background-color: #d63445; }
        .badge-masuk { background-color: #e8f5e9; color: #2e7d32; font-weight: 600; padding: 5px 12px; border-radius: 6px; }
        .badge-keluar { background-color: #ffebee; color: #c62828; font-weight: 600; padding: 5px 12px; border-radius: 6px; }
    </style>
</head>
<body class="d-flex">

    <div class="sidebar">
        <div class="sidebar-brand">Logis<span>Tix</span></div>
        <div class="d-flex flex-column w-100">
            <div class="nav-item {{ request()->routeIs('dashboard') ? 'active-menu' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </a>
            </div>
            <div class="nav-item {{ request()->routeIs('barang.index') ? 'active-menu' : '' }}">
                <a href="{{ route('barang.index') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>
                    Barang
                </a>
            </div>
            <div class="nav-item {{ request()->routeIs('stok.index') ? 'active-menu' : '' }}">
                <a href="{{ route('stok.index') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    Stok
                </a>
            </div>
            <div class="nav-item {{ request()->routeIs('laporan.index') ? 'active-menu' : '' }}">
                <a href="{{ route('laporan.index') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    Laporan
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
            <small class="me-4">{{ now()->format('l, d M Y') }} <span style="color: #f36f21;">●</span></small>
        </div>

        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card-custom">
                        <h5 class="fw-bold mb-4" style="color: #2e7d32;">↑ Stok Masuk</h5>
                        <form action="{{ route('stok.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis" value="masuk">
                            <div class="mb-3">
                                <label class="form-label text-muted">Barang</label>
                                <select name="item_id" class="form-select bg-light" required>
                                    <option value="" disabled selected>Pilih Barang...</option>
                                    @foreach($items->groupBy('kategori') as $kategori => $barangList)
                                        <optgroup label="📦 KATEGORI: {{ strtoupper($kategori ?: 'UMUM') }}">
                                            @foreach($barangList as $item)
                                                <option value="{{ $item->id }}">{{ $item->kode_barang }} - {{ $item->nama_barang }} (Stok: {{ $item->stok }})</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Jumlah Masuk</label>
                                <input type="number" name="jumlah" class="form-control bg-light" placeholder="0" min="1" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted">Keterangan / Supplier</label>
                                <input type="text" name="keterangan" class="form-control bg-light" placeholder="Restock barang">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn-merah">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card-custom">
                        <h5 class="fw-bold mb-4" style="color: #c62828;">↓ Stok Keluar</h5>
                        <form action="{{ route('stok.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis" value="keluar">
                            <div class="mb-3">
                                <label class="form-label text-muted">Barang</label>
                                <select name="item_id" class="form-select bg-light" required>
                                    <option value="" disabled selected>Pilih Barang...</option>
                                    @foreach($items->groupBy('kategori') as $kategori => $barangList)
                                        <optgroup label="📦 KATEGORI: {{ strtoupper($kategori ?: 'UMUM') }}">
                                            @foreach($barangList as $item)
                                                <option value="{{ $item->id }}">{{ $item->kode_barang }} - {{ $item->nama_barang }} (Stok: {{ $item->stok }})</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Jumlah Keluar</label>
                                <input type="number" name="jumlah" class="form-control bg-light" placeholder="0" min="1" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted">Keterangan / Keperluan</label>
                                <input type="text" name="keterangan" class="form-control bg-light" placeholder="Barang keluar">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn-merah">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-custom">
                <h5 class="fw-bold mb-4">Riwayat Transaksi Stok</h5>
                <div class="table-responsive">
                    <table class="table table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $t)
                            <tr class="border-bottom">
                                <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                                <td><span class="badge bg-secondary font-monospace">{{ $t->item->kode_barang ?? '-' }}</span></td>
                                <td class="text-start fw-semibold">{{ $t->item->nama_barang ?? 'Barang Terhapus' }}</td>
                                <td>
                                    <span class="{{ $t->jenis == 'masuk' ? 'badge-masuk' : 'badge-keluar' }}">
                                        {{ strtoupper($t->jenis) }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ $t->jumlah }}</td>
                                <td class="text-muted text-start">{{ $t->keterangan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi stok dilakukan.</td>
                            </tr>
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
