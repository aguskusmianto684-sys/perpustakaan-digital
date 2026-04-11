@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')
    <div class="container">

        {{-- ================= HEADER ================= --}}
        <h4 class="mb-4 fw-semibold">
            Selamat Datang, {{ Auth::user()->username }}
        </h4>

        {{-- ================= CARD STATISTIK ================= --}}
        <div class="row">

            {{-- TOTAL --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#4e73df,#224abe); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small>TOTAL PINJAMAN</small>
                            <h2 class="fw-bold">{{ $total }}</h2>
                        </div>
                        <i class="ti ti-book" style="font-size:55px;"></i>
                    </div>
                </div>
            </div>

            {{-- DIPINJAM --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#1cc88a,#13855c); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small>SEDANG DIPINJAM</small>
                            <h2 class="fw-bold">{{ $dipinjam }}</h2>
                        </div>
                        <i class="ti ti-bookmark" style="font-size:55px;"></i>
                    </div>
                </div>
            </div>

            {{-- DIKEMBALIKAN --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#36b9cc,#258391); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small>DIKEMBALIKAN</small>
                            <h2 class="fw-bold">{{ $kembali }}</h2>
                        </div>
                        <i class="ti ti-check" style="font-size:55px;"></i>
                    </div>
                </div>
            </div>

            {{-- TERLAMBAT --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#e74a3b,#c0392b); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small>TERLAMBAT</small>
                            <h2 class="fw-bold">{{ $terlambat }}</h2>
                        </div>
                        <i class="ti ti-alert-circle" style="font-size:55px;"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= ALERT ================= --}}
        {{-- muncul jika ada keterlambatan --}}
        @if ($terlambat > 0)
            <a href="/anggota/peminjaman" class="text-decoration-none">
                <div class="alert alert-danger shadow-sm d-flex justify-content-between align-items-center">
                    <div>
                        ⚠️ Kamu punya <b>{{ $terlambat }}</b> buku terlambat!
                        <br><small>Klik untuk melihat</small>
                    </div>
                    <i class="ti ti-arrow-right fs-3"></i>
                </div>
            </a>
        @endif

        {{-- ================= SYARAT PEMINJAMAN ================= --}}
        <div class="card mt-4 border-0 shadow" style="background: linear-gradient(45deg,#6c757d,#495057); color:white;">

            <div class="card-body">

                <h5 class="mb-3">
                    ℹ️ Informasi Aturan Peminjaman
                </h5>

                <ol class="mb-0">
                    <li>Waktu peminjaman maksimal <b>7 hari</b></li>
                    <li>Maksimal meminjam <b>3 buku</b></li>
                    <li>Denda keterlambatan <b>Rp 1.000 / hari</b></li>
                    <li>Wajib mengembalikan tepat waktu</li>
                    <li>Jika terlambat, wajib membayar denda</li>
                </ol>

            </div>
        </div>

        {{-- ================= QUICK ACTION ================= --}}
        {{-- tombol cepat --}}
        <div class="mb-3">
            <a href="/anggota/buku" class="btn btn-primary">
                Cari Buku
            </a>

            <a href="/anggota/peminjaman" class="btn btn-success">
                Buku Saya
            </a>
        </div>

        {{-- ================= BUKU TERAKHIR ================= --}}
        <div class="card shadow border-0 mt-3">
            <div class="card-body">

                <h5 class="mb-3">Buku Terakhir Dipinjam</h5>

                @if ($last)
                    <div class="d-flex align-items-center gap-3">

                        <img src="{{ asset('uploads/buku/' . ($last->buku->gambar ?? 'default.png')) }}" width="70"
                            class="rounded shadow-sm">

                        <div>
                            <b>{{ $last->buku->judul ?? '-' }}</b><br>
                            <small class="text-muted">Tgl: {{ $last->tgl_pinjam }}</small>
                        </div>

                    </div>
                @else
                    <p class="text-muted">Belum ada peminjaman</p>
                @endif

            </div>
        </div>

    </div>
@endsection
