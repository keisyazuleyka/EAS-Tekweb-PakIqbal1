<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogisTix - Data Barang</title>
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
    </style>
</head>
<body class="d-flex">

    <div class="sidebar">
        <div class="sidebar-brand">Logis<span>Tix</span></div>
        <div class="d-flex flex-column w-100">
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> Dashboard
                </a>
            </div>
            <div class="nav-item active-menu">
                <a href="{{ route('barang.index') }}" class="nav-link">
                    <svg viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg> Barang
                </a>
            </div>
            <div class="nav-item">
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
            <div class="card-custom mb-4">
                <h5 class="fw-bold mb-3">Tambah Barang</h5>
                <form action="{{ route('barang.store') }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-4"><input type="text" name="nama_barang" class="form-control" placeholder="Nama Barang" required></div>
                    <div class="col-md-3">
                        <select name="kategori" class="form-select" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Fashion">Fashion</option>
                            <option value="Meubel">Meubel</option>
                            <option value="ATK">ATK</option>
                            <option value="Aksesoris">Aksesoris</option>
                            <option value="Makeup">Makeup</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="number" name="stok" class="form-control" placeholder="Stok" required></div>
                    <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Simpan</button></div>
                </form>
            </div>

            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold">Daftar Barang</h5>
                    <input type="text" id="searchInput" class="form-control w-25" placeholder="Cari nama barang...">
                </div>
                <table class="table table-hover text-center" id="barangTable">
                    <thead class="table-light">
                        <tr><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Stok</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->kode_barang }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->kategori ?? '-' }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>
                                <form action="{{ route('barang.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#barangTable tbody tr');
            rows.forEach(row => {
                let name = row.cells[1].textContent.toLowerCase();
                row.style.display = name.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
