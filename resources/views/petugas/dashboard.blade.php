@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')

    <div class="container">

        {{-- ================= HEADER ================= --}}
        <h4 class="mb-4 fw-bold">
            👋 Dashboard Petugas
        </h4>

        {{-- ================= CARD STATISTIK ================= --}}
        <div class="row">

            {{-- TOTAL BUKU --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#4e73df,#224abe); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <small>TOTAL BUKU</small>
                            <h2 class="fw-bold">{{ $totalBuku }}</h2>
                        </div>
                        <i class="ti ti-book" style="font-size:50px;"></i>
                    </div>
                </div>
            </div>

            {{-- TOTAL ANGGOTA --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#1cc88a,#13855c); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <small>ANGGOTA</small>
                            <h2 class="fw-bold">{{ $totalAnggota }}</h2>
                        </div>
                        <i class="ti ti-users" style="font-size:50px;"></i>
                    </div>
                </div>
            </div>

            {{-- PEMINJAMAN AKTIF --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#36b9cc,#258391); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <small>DIPINJAM</small>
                            <h2 class="fw-bold">{{ $peminjamanAktif }}</h2>
                        </div>
                        <i class="ti ti-bookmark" style="font-size:50px;"></i>
                    </div>
                </div>
            </div>

            {{-- TERLAMBAT --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#e74a3b,#c0392b); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <small>TERLAMBAT</small>
                            <h2 class="fw-bold">{{ $terlambat }}</h2>
                        </div>
                        <i class="ti ti-alert-circle" style="font-size:50px;"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= ALERT INTERAKTIF ================= --}}
        {{-- Menampilkan kondisi penting dan bisa diklik --}}
        @if ($terlambat > 0 || $menunggu > 0 || $hariIni > 0)
            <div class="mb-3">

                {{-- ALERT TERLAMBAT --}}
                @if ($terlambat > 0)
                    <a href="/petugas/peminjaman" class="text-decoration-none">
                        <div class="alert alert-danger shadow-sm d-flex justify-content-between align-items-center">
                            <div>
                                ⚠️ Ada <b>{{ $terlambat }}</b> peminjaman terlambat!
                                <br><small>Klik untuk melihat</small>
                            </div>
                            <i class="ti ti-arrow-right fs-3"></i>
                        </div>
                    </a>
                @endif

                {{-- ALERT MENUNGGU --}}
                @if ($menunggu > 0)
                    <a href="/petugas/peminjaman" class="text-decoration-none">
                        <div class="alert alert-warning shadow-sm d-flex justify-content-between align-items-center">
                            <div>
                                ⏳ Ada <b>{{ $menunggu }}</b> menunggu konfirmasi
                                <br><small>Klik untuk proses</small>
                            </div>
                            <i class="ti ti-arrow-right fs-3"></i>
                        </div>
                    </a>
                @endif

                {{-- NOTIFIKASI PENGEMBALIAN --}}
                @if ($pengajuanPengembalian > 0)
                    <a href="/petugas/peminjaman" class="text-decoration-none">
                        <div class="alert alert-info shadow-sm d-flex justify-content-between align-items-center">
                            <div>
                                🔄 Ada <b>{{ $pengajuanPengembalian }}</b> pengajuan pengembalian
                                <br><small>Klik untuk konfirmasi</small>
                            </div>
                            <i class="ti ti-arrow-right fs-3"></i>
                        </div>
                    </a>
                @endif

                {{-- ALERT HARI INI --}}
                @if ($hariIni > 0)
                    <div class="alert alert-info shadow-sm d-flex justify-content-between align-items-center">
                        <div>
                            📥 Hari ini ada <b>{{ $hariIni }}</b> peminjaman baru
                        </div>
                        <i class="ti ti-info-circle fs-3"></i>
                    </div>
                @endif

            </div>
        @endif

        {{-- ================= QUICK ACTION ================= --}}
        {{-- Tombol cepat biar petugas ga bolak balik menu --}}
        <div class="mb-3">
            <a href="/petugas/peminjaman/create" class="btn btn-primary">
                + Tambah Peminjaman
            </a>

            <a href="/petugas/peminjaman" class="btn btn-success">
                Kelola Peminjaman
            </a>
        </div>

        {{-- ================= AKTIVITAS TERBARU ================= --}}
        <div class="card shadow border-0">
            <div class="card-body">

                <h5 class="mb-3">Aktivitas Terbaru</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">

                        <thead class="table-light">
                            <tr>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tgl</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($latest as $p)
                                <tr>
                                    <td>{{ $p->anggota->nama ?? '-' }}</td>
                                    <td>{{ $p->buku->judul ?? '-' }}</td>
                                    <td>{{ $p->tgl_pinjam }}</td>
                                    <td>

                                        {{-- STATUS DINAMIS --}}
                                        @if ($p->status == 'dipinjam' && now()->gt($p->tgl_kembali))
                                            <span class="badge bg-danger">Terlambat</span>
                                        @elseif($p->status == 'dipinjam')
                                            <span class="badge bg-primary">Dipinjam</span>
                                        @elseif($p->status == 'menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($p->status == 'ditolak')
                                            <span class="badge bg-dark">Ditolak</span>
                                        @else
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @endif

                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada aktivitas
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

@endsection
