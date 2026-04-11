@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')

    <div class="container">

        <h4 class="mb-4">Detail Peminjaman</h4>

        <div class="card shadow-sm">
            <div class="card-body">

                <table class="table">

                    <tr>
                        <th>Nama Anggota</th>
                        <td>{{ $data->anggota->nama }}</td>
                    </tr>

                    <tr>
                        <th>Buku</th>
                        <td>{{ $data->buku->judul }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Pinjam</th>
                        <td>{{ $data->tgl_pinjam }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Kembali</th>
                        <td>{{ $data->tgl_kembali }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>{{ $data->status }}</td>
                    </tr>

                    <tr>
                        <th>Denda</th>
                        <td>

                            @php
                                $denda = 0;
                                $hari = 0;

                                // 🔥 kalau sudah dikembalikan → hitung dari tgl_dikembalikan
                                if ($data->status == 'dikembalikan' && $data->tgl_dikembalikan) {
                                    if ($data->tgl_dikembalikan > $data->tgl_kembali) {
                                        $hari = \Carbon\Carbon::parse($data->tgl_kembali)
                                            ->startOfDay()
                                            ->diffInDays(\Carbon\Carbon::parse($data->tgl_dikembalikan)->startOfDay());

                                        $denda = $hari * 1000;
                                    }
                                }
                                // 🔥 kalau masih dipinjam (realtime)
                                elseif ($data->status == 'dipinjam' && now()->gt($data->tgl_kembali)) {
                                    $hari = \Carbon\Carbon::parse($data->tgl_kembali)
                                        ->startOfDay()
                                        ->diffInDays(now()->startOfDay());

                                    $denda = $hari * 1000;
                                }
                            @endphp

                            @if ($denda > 0)
                                <span class="text-danger fw-semibold">
                                    Rp {{ number_format($denda) }}
                                </span>

                                {{-- 🔥 KETERANGAN --}}
                                @if ($data->status == 'dikembalikan')
                                    <br>
                                    <small class="text-success">
                                        ✔ Sudah Lunas
                                    </small>
                                @else
                                    <br>
                                    <small class="text-danger">
                                        Terlambat {{ $hari }} hari
                                    </small>
                                @endif
                            @else
                                <span class="text-success">Tidak ada denda</span>
                            @endif

                        </td>
                    </tr>

                </table>

                <a href="/petugas/peminjaman" class="btn btn-secondary">
                    Kembali
                </a>

            </div>
        </div>

    </div>

@endsection
