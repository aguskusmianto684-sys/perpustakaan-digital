@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-3">
            <i class="ti ti-book"></i> Data Peminjaman
        </h4>

        <a href="/petugas/peminjaman/create" class="btn btn-primary mb-3">
            + Tambah Peminjaman
        </a>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle datatable">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($peminjaman as $index => $p)

                    <tr>

                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->anggota->nama ?? '-' }}</td>
                        <td>{{ $p->buku->judul ?? '-' }}</td>
                        <td>{{ $p->tgl_pinjam }}</td>
                        <td>{{ $p->tgl_kembali }}</td>

                        {{-- 🔥 DENDA --}}
                        <td>
                            @php
                                $denda = 0;
                                $hari = 0;

                                if ($p->status == 'dipinjam' && now()->gt($p->tgl_kembali)) {

                                    $hari = \Carbon\Carbon::parse($p->tgl_kembali)
                                            ->startOfDay()
                                            ->diffInDays(now()->startOfDay());

                                    $denda = $hari * 1000;
                                }
                            @endphp

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

                        {{-- 🔥 STATUS --}}
                        <td>

                            @if($p->status == 'dipinjam' && now()->gt($p->tgl_kembali))
                                <span class="badge bg-danger">Terlambat</span>

                            @elseif($p->status == 'dipinjam')
                                <span class="badge bg-primary">Dipinjam</span>

                            @elseif($p->status == 'menunggu')
                                <span class="badge bg-warning text-dark">Menunggu</span>

                            @elseif($p->status == 'menunggu pengembalian')
                                <span class="badge bg-info">Menunggu Pengembalian</span>

                            @elseif($p->status == 'ditolak')
                                <span class="badge bg-dark">Ditolak</span>

                            @elseif($p->status == 'dikembalikan')
                                <span class="badge bg-success">Dikembalikan</span>

                            @else
                                <span class="badge bg-secondary">Tidak diketahui</span>
                            @endif

                        </td>

                        {{-- 🔥 AKSI CLEAN --}}
                        <td class="text-center">

                            <div class="d-flex justify-content-center gap-1">

                                {{-- DETAIL --}}
                                <a href="/petugas/peminjaman/detail/{{ $p->id_peminjaman }}"
                                   class="btn btn-sm btn-info"
                                   data-bs-toggle="tooltip"
                                   title="Detail">
                                    <i class="ti ti-eye"></i>
                                </a>

                                {{-- DROPDOWN --}}
                                <div class="dropdown">

                                    <button class="btn btn-sm btn-secondary"
                                            data-bs-toggle="dropdown"
                                            title="Aksi">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        @if($p->status == 'menunggu')

                                            <li>
                                                <a class="dropdown-item text-success"
                                                   href="/petugas/peminjaman/konfirmasi/{{ $p->id_peminjaman }}">
                                                    ✔ Konfirmasi
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item text-danger"
                                                   href="/petugas/peminjaman/tolak/{{ $p->id_peminjaman }}"
                                                   onclick="return confirm('Yakin ingin menolak?')">
                                                    ✖ Tolak
                                                </a>
                                            </li>

                                        @elseif($p->status == 'menunggu pengembalian')

                                            <li>
                                                <a class="dropdown-item text-success"
                                                   href="/petugas/peminjaman/kembalikan/{{ $p->id_peminjaman }}">
                                                    ✔ Konfirmasi Pengembalian
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item text-danger"
                                                   href="/petugas/peminjaman/tolak-pengembalian/{{ $p->id_peminjaman }}">
                                                    ✖ Tolak
                                                </a>
                                            </li>

                                        @else
                                            <li>
                                                <span class="dropdown-item text-muted">
                                                    Tidak ada aksi
                                                </span>
                                            </li>
                                        @endif

                                    </ul>

                                </div>

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
</div>

@endsection


{{-- 🔥 TOOLTIP AKTIF --}}
@push('js')
<script>
document.addEventListener("DOMContentLoaded", function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el)
    })
});
</script>
@endpush
