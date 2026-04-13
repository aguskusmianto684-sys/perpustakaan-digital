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

                    <tr>
                        <th width="200">Nama Anggota</th>
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
                        <th>Batas Kembali</th>
                        <td>{{ $data->tgl_kembali }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Dikembalikan</th>
                        <td>{{ $data->tgl_dikembalikan ?? '-' }}</td>
                    </tr>

                    {{-- STATUS --}}
                    <tr>
                        <th>Status</th>
                        <td>

                            @if ($data->status == 'ditolak')
                                <span class="badge bg-dark">Ditolak</span>

                            @elseif($data->status == 'dikembalikan')

                                @php
                                    $terlambat = $data->tgl_dikembalikan && $data->tgl_dikembalikan > $data->tgl_kembali;
                                @endphp

                                @if ($terlambat)
                                    <span class="badge bg-danger">Terlambat</span><br>

                                    @if ($data->denda == 0)
                                        <small class="text-success">Sudah Lunas</small>
                                    @else
                                        <small class="text-warning">Belum Dibayar</small>
                                    @endif

                                @else
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @endif

                            @else
                                <span class="badge bg-secondary">Tidak diketahui</span>
                            @endif

                        </td>
                    </tr>

                    {{-- DENDA --}}
                    <tr>
                        <th>Denda</th>
                        <td>

                            @php
                                $dendaAsli = $data->pengembalian->denda ?? 0;
                            @endphp

                            @if ($data->status == 'dikembalikan')

                                @if ($dendaAsli > 0)
                                    <span class="text-danger fw-semibold">
                                        Rp {{ number_format($dendaAsli) }}
                                    </span>
                                    <br>

                                    @if ($data->denda == 0)
                                        <small class="text-success">
                                            ✔ Sudah Lunas
                                        </small>
                                    @else
                                        <small class="text-warning">
                                            Belum Dibayar
                                        </small>
                                    @endif

                                @else
                                    <span class="text-success">
                                        Tidak ada denda
                                    </span>
                                @endif

                            @else
                                -
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
