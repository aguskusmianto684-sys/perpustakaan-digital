@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-kepala')
@endsection

@section('content')
    <div class="container">

        {{-- HEADER --}}
        <h4 class="mb-4 fw-bold">
            Dashboard Kepala Perpustakaan
        </h4>

        {{-- NOTIFIKASI PENGEMBALIAN --}}
        @if (isset($pengajuanPengembalian) && $pengajuanPengembalian > 0)
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <div>
                    🔔 Ada <strong>{{ $pengajuanPengembalian }}</strong> pengajuan pengembalian menunggu konfirmasi
                </div>
                <a href="/petugas/peminjaman" class="btn btn-sm btn-primary">
                    Lihat
                </a>
            </div>
        @endif

        {{-- ================= CARD ================= --}}
        <div class="row">

            {{-- TOTAL BUKU --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#4e73df,#224abe); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <small>TOTAL BUKU</small>
                            <h2>{{ $totalBuku }}</h2>
                        </div>
                        <i class="ti ti-book" style="font-size:55px;"></i>
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
                            <h2>{{ $totalAnggota }}</h2>
                        </div>
                        <i class="ti ti-users" style="font-size:55px;"></i>
                    </div>
                </div>
            </div>

            {{-- TOTAL PEMINJAMAN --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#36b9cc,#258391); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <small>TOTAL PEMINJAMAN</small>
                            <h2>{{ $totalPeminjaman }}</h2>
                        </div>
                        <i class="ti ti-chart-bar" style="font-size:55px;"></i>
                    </div>
                </div>
            </div>

            {{-- BULAN INI --}}
            <div class="col-md-3 mb-3">
                <div class="card text-white shadow border-0"
                    style="background: linear-gradient(45deg,#f6c23e,#dda20a); border-radius:15px;">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <small>BULAN INI</small>
                            <h2>{{ $bulanIni }}</h2>
                        </div>
                        <i class="ti ti-calendar" style="font-size:55px;"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= TOP BUKU ================= --}}
        <div class="card shadow border-0 mt-3">
            <div class="card-body">

                <h5 class="mb-3">Buku Paling Populer</h5>

                @foreach ($populer as $p)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <div>{{ $p->buku->judul ?? '-' }}</div>
                        <span class="badge bg-primary">{{ $p->total }}x dipinjam</span>
                    </div>
                @endforeach

            </div>
        </div>

        {{-- ================= AKTIVITAS ================= --}}
        <div class="card shadow border-0 mt-3">
            <div class="card-body">

                <h5 class="mb-3">Aktivitas Terbaru</h5>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Petugas</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($latest as $p)
                            <tr>
                                <td>{{ $p->anggota->nama ?? '-' }}</td>
                                <td>{{ $p->buku->judul ?? '-' }}</td>
                                <td>{{ $p->petugas->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ $p->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
