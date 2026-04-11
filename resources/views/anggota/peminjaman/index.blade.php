@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')
    <div class="container">

        {{-- 🔥 JUDUL --}}
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
                        <th width="140">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($data as $index => $d)
                        <tr>

                            {{-- NOMOR --}}
                            <td>{{ $index + 1 }}</td>

                            {{-- BUKU --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('uploads/buku/' . $d->gambar) }}" width="40"
                                        style="border-radius:6px">
                                    <span>{{ $d->judul }}</span>
                                </div>
                            </td>

                            {{-- TANGGAL --}}
                            <td>{{ $d->tgl_pinjam }}</td>
                            <td>{{ $d->tgl_kembali }}</td>

                            <td>
                                @php
                                    $denda = 0;
                                    $hari = 0;

                                    // 🔥 hitung realtime kalau masih dipinjam & terlambat
                                    if ($d->status == 'dipinjam' && now()->gt($d->tgl_kembali)) {
                                        $hari = floor(\Carbon\Carbon::parse($d->tgl_kembali)->diffInDays(now()));

                                        $denda = $hari * 1000;
                                    } else {
                                        $denda = $d->denda;
                                    }
                                @endphp

                                @if ($denda > 0)
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

                            {{-- STATUS --}}
                            <td>

                                @if ($d->status == 'dipinjam' && now()->gt($d->tgl_kembali))
                                    <span class="badge bg-danger">Terlambat</span>
                                @elseif($d->status == 'dipinjam')
                                    <span class="badge bg-primary">Dipinjam</span>
                                @elseif($d->status == 'menunggu')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($d->status == 'menunggu pengembalian')
                                    <span class="badge bg-info">Menunggu Konfirmasi</span>
                                @elseif($d->status == 'ditolak')
                                    <span class="badge bg-dark">Ditolak</span>
                                @elseif($d->status == 'dikembalikan')
                                    <span class="badge bg-success">Dikembalikan</span>
                                @else
                                    <span class="badge bg-secondary">Tidak diketahui</span>
                                @endif

                            </td>

                            {{-- 🔥 AKSI BARU (CLEAN + ICON) --}}
                            <td class="text-center">

                                @if ($d->status == 'dipinjam')
                                    <a href="/anggota/pengembalian/{{ $d->id_peminjaman }}" class="btn btn-sm btn-warning"
                                        title="Ajukan Pengembalian"
                                        onclick="return confirm('Ajukan pengembalian buku ini?')">

                                        <i class="ti ti-rotate"></i> Ajukan
                                    </a>
                                @elseif($d->status == 'menunggu pengembalian')
                                    <span class="badge bg-info">
                                        <i class="ti ti-clock"></i> Menunggu
                                    </span>
                                @elseif($d->status == 'dikembalikan')
                                    <span class="badge bg-success">
                                        <i class="ti ti-check"></i> Selesai
                                    </span>
                                @elseif($d->status == 'ditolak')
                                    <span class="badge bg-danger">
                                        <i class="ti ti-x"></i> Ditolak
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
@endsection


{{-- 🔥 STYLE TAMBAHAN --}}
@push('css')
    <style>
        .btn-sm i {
            font-size: 14px;
        }
    </style>
@endpush
