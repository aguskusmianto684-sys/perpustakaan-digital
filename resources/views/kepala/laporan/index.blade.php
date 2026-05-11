@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-kepala')
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        {{-- 🔥 JUDUL HALAMAN --}}
        <h4 class="mb-3">Laporan Peminjaman & Pengembalian</h4>

        {{-- 🔥 FILTER --}}
        <form method="GET" class="mb-3 d-flex gap-2">

            <select name="bulan" class="form-control">
                <option value="">-- Pilih Bulan --</option>
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="tahun" class="form-control">
                <option value="">-- Pilih Tahun --</option>
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button class="btn btn-primary">Filter</button>
            <a href="/kepala/laporan" class="btn btn-secondary">Reset</a>

        </form>

        {{-- 🔥 INFO FILTER --}}
        @if(request('bulan') || request('tahun'))
            <div class="alert alert-info">
                Menampilkan laporan:
                {{ request('bulan') ? \Carbon\Carbon::create()->month((int) request('bulan'))->translatedFormat('F') : 'Semua Bulan' }}
                {{ request('tahun') ?? '' }}
            </div>
        @endif

        {{-- 🔥 HEADER + PDF --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="mb-0">Data Laporan</h5>
                <small class="text-muted">Export laporan sesuai filter</small>
            </div>

            <a href="/kepala/laporan/pdf?bulan={{ request('bulan') }}&tahun={{ request('tahun') }}"
               class="btn btn-danger shadow-sm"
               style="border-radius:10px; padding:8px 15px;">

                <i class="ti ti-file-download"></i>
                Download PDF

            </a>

        </div>

        {{-- 🔥 TABLE --}}
        <div class="table-responsive">
            <table id="laporanTable" class="table table-bordered table-hover">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status Pengembalian</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($data as $index => $d)

                    @php
                        $denda = 0;
                        $hari = 0;

                        // 🔥 MASIH DIPINJAM & TERLAMBAT
                        if ($d->status == 'dipinjam' && now()->gt($d->tgl_kembali)) {

                            $hari = \Carbon\Carbon::parse($d->tgl_kembali)
                                ->startOfDay()
                                ->diffInDays(now()->startOfDay());

                            $denda = $hari * 1000;
                        }

                        // 🔥 SUDAH DIKEMBALIKAN & TERLAMBAT
                        elseif ($d->pengembalian && $d->pengembalian->tgl_pengembalian > $d->tgl_kembali) {

                            $hari = \Carbon\Carbon::parse($d->tgl_kembali)
                                ->startOfDay()
                                ->diffInDays(
                                    \Carbon\Carbon::parse($d->pengembalian->tgl_pengembalian)->startOfDay()
                                );

                            $denda = $hari * 1000;
                        }
                    @endphp

                    <tr>

                        <td>{{ $index + 1 }}</td>
                        <td>{{ $d->anggota->nama ?? '-' }}</td>
                        <td>{{ $d->buku->judul ?? '-' }}</td>
                        <td>{{ $d->petugas->nama ?? '-' }}</td>

                        {{-- STATUS --}}
                        <td>
                            @if($d->status == 'menunggu')
                                <span class="badge bg-warning text-dark">Menunggu</span>

                            @elseif($d->status == 'dipinjam')
                                <span class="badge bg-primary">Dipinjam</span>

                            @elseif($d->status == 'ditolak')
                                <span class="badge bg-dark">Ditolak</span>

                            @else
                                <span class="badge bg-success">Dikembalikan</span>
                            @endif
                        </td>

                        {{-- 🔥 DENDA FIX --}}
                        <td>
                            @if($denda > 0)
                                <span class="text-danger fw-semibold">
                                    Rp {{ number_format($denda) }}
                                </span>
                                <br>
                                <small class="text-danger">
                                    Terlambat {{ $hari }} hari
                                </small>
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ $d->tgl_pinjam }}</td>

                        {{-- TGL PENGEMBALIAN --}}
                        <td>
                            {{ $d->pengembalian->tgl_pengembalian ?? '-' }}
                        </td>

                        {{-- 🔥 STATUS PENGEMBALIAN FIX --}}
                        <td>

                            @if($d->status == 'dipinjam')
                                <span class="badge bg-warning">Belum Kembali</span>

                            @elseif($d->pengembalian)

                                @if($d->pengembalian->tgl_pengembalian > $d->tgl_kembali)
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @endif

                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
