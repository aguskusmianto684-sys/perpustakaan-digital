@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')

    <div class="container">

        <h4 class="mb-4">Detail Riwayat Peminjaman</h4>

        <div class="card shadow-sm">
            <div class="card-body">

                <table class="table">

                    {{-- ANGGOTA --}}
                    <tr>
                        <th width="200">Nama Anggota</th>
                        <td>{{ $data->anggota->nama }}</td>
                    </tr>

                    {{-- BUKU --}}
                    <tr>
                        <th>Buku</th>
                        <td>{{ $data->buku->judul }}</td>
                    </tr>

                    {{-- TANGGAL --}}
                    <tr>
                        <th>Tanggal Pinjam</th>
                        <td>{{ $data->tgl_pinjam }}</td>
                    </tr>

                    <tr>
                        <th>Batas Kembali</th>
                        <td>{{ $data->tgl_kembali }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Dikembalikan</th>
                        <td>{{ $data->tgl_dikembalikan ?? '-' }}</td>
                    </tr>

                    {{-- 🔥 STATUS FINAL --}}
                    <tr>
                        <th>Status</th>
                        <td>

                            @if ($data->status == 'ditolak')
                                <span class="badge bg-dark">Ditolak</span>
                            @elseif($data->status == 'dikembalikan')
                                @php
                                    $terlambat = false;

                                    if ($data->tgl_dikembalikan && $data->tgl_dikembalikan > $data->tgl_kembali) {
                                        $terlambat = true;
                                    }
                                @endphp

                                @if ($terlambat)
                                    <span class="badge bg-danger">Terlambat</span>
                                    <br>
                                    <small class="text-muted">Sudah dikembalikan</small>
                                @else
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">Tidak diketahui</span>
                            @endif

                        </td>
                    </tr>

                    {{-- 🔥 DENDA FINAL --}}
                    <tr>
                        <th>Denda</th>
                        <td>

                            @php
                                $denda = 0;
                                $hari = 0;

                                if ($data->tgl_dikembalikan && $data->tgl_dikembalikan > $data->tgl_kembali) {
                                    $hari = \Carbon\Carbon::parse($data->tgl_kembali)
                                        ->startOfDay()
                                        ->diffInDays(\Carbon\Carbon::parse($data->tgl_dikembalikan)->startOfDay());

                                    $denda = $hari * 1000;
                                }
                            @endphp

                            @if ($denda > 0)
                                <span class="text-danger fw-semibold">
                                    Rp {{ number_format($denda) }}
                                </span>
                                <br>
                                <small class="text-success">
                                    ✔ Sudah Lunas
                                </small>
                            @else
                                <span class="text-success">Tidak ada denda</span>
                            @endif

                        </td>
                    </tr>

                </table>

                <a href="/petugas/riwayat" class="btn btn-secondary">
                    Kembali
                </a>

            </div>
        </div>

    </div>

@endsection
