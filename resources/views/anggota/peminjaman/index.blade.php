@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection
@section('content')

<div class="container">

    {{-- judul halaman --}}
    <h4 class="mb-4">
        <i class="ti ti-book"></i> Buku Saya
    </h4>

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle datatable">

            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Denda</th>
                    <th>Status</th>
                    <th>Aksi</th> {{-- 🔥 TAMBAH --}}
                </tr>
            </thead>

            <tbody>

                @foreach($data as $index => $d)

                <tr>

                    {{-- nomor --}}
                    <td>{{ $index + 1 }}</td>

                    {{-- info buku --}}
                    <td>
                        <img src="{{ asset('uploads/buku/'.$d->gambar) }}" width="40">
                        {{ $d->judul }}
                    </td>

                    {{-- tanggal --}}
                    <td>{{ $d->tgl_pinjam }}</td>
                    <td>{{ $d->tgl_kembali }}</td>

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

                    {{-- status --}}
                    <td>

                        @if($d->status == 'dipinjam' && now()->gt($d->tgl_kembali))
                            <span class="badge bg-danger">Terlambat</span>

                        @elseif($d->status == 'dipinjam')
                            <span class="badge bg-primary">Dipinjam</span>

                        @elseif($d->status == 'menunggu')
                            <span class="badge bg-warning text-dark">Menunggu</span>

                        @elseif($d->status == 'menunggu pengembalian') {{-- 🔥 TAMBAH --}}
                            <span class="badge bg-info">Menunggu Konfirmasi</span>

                        @elseif($d->status == 'ditolak')
                            <span class="badge bg-dark">Ditolak</span>

                        @elseif($d->status == 'dikembalikan')
                            <span class="badge bg-success">Dikembalikan</span>

                        @else
                            <span class="badge bg-secondary">Tidak diketahui</span>
                        @endif

                    </td>

                    {{-- 🔥 AKSI --}}
                    <td>

                        @if($d->status == 'dipinjam')
                            <a href="/anggota/pengembalian/{{ $d->id_peminjaman }}"
                               class="btn btn-warning btn-sm"
                               onclick="return confirm('Ajukan pengembalian buku ini?')">
                                Ajukan
                            </a>

                        @elseif($d->status == 'menunggu pengembalian')
                            <span class="text-muted">Menunggu</span>

                        @else
                            -
                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
