@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-kepala')
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-3">Laporan Peminjaman & Pengembalian</h4>

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
                        <th>Tanggal Kembali</th> {{-- 🔥 TAMBAH --}}
                        <th>Status Pengembalian</th> {{-- 🔥 TAMBAH --}}
                    </tr>
                </thead>

                <tbody>

                    @foreach($data as $index => $d)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        {{-- relasi --}}
                        <td>{{ $d->anggota->nama ?? '-' }}</td>
                        <td>{{ $d->buku->judul ?? '-' }}</td>
                        <td>{{ $d->petugas->nama ?? '-' }}</td>

                        {{-- status peminjaman --}}
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

                        {{-- denda --}}
                        <td>
                            @if($d->denda > 0)
                                <span class="text-danger">
                                    Rp {{ number_format($d->denda) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>

                        {{-- tanggal pinjam --}}
                        <td>{{ $d->tgl_pinjam }}</td>

                        {{-- 🔥 tanggal pengembalian --}}
                        <td>
                            {{ $d->pengembalian->tgl_pengembalian ?? '-' }}
                        </td>

                        {{-- 🔥 status pengembalian --}}
                        <td>
                            @if($d->pengembalian)

                                @if($d->pengembalian->status == 'terlambat')
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @endif

                            @else
                                <span class="badge bg-warning">Belum Kembali</span>
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
