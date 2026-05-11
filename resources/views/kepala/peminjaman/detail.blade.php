@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-kepala')
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-3">
            <i class="ti ti-book"></i> Detail Peminjaman
        </h4>

        <table class="table table-bordered">

            <tr>
                <th width="200">Anggota</th>
                <td>{{ $data->anggota->nama ?? '-' }}</td>
            </tr>

            <tr>
                <th>Buku</th>
                <td>{{ $data->buku->judul ?? '-' }}</td>
            </tr>

            <tr>
                <th>Petugas</th>
                <td>
                    @if($data->petugas)
                        {{ $data->petugas->nama }}
                    @else
                        <span class="badge bg-secondary">
                            Belum dikonfirmasi
                        </span>
                    @endif
                </td>
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

            {{-- 🔥 STATUS --}}
            <tr>
                <th>Status</th>
                <td>

                    @if($data->status == 'menunggu')
                        <span class="badge bg-warning text-dark">Menunggu</span>

                    @elseif($data->status == 'dipinjam')

                        @php
                            $terlambat = \Carbon\Carbon::parse($data->tgl_kembali)
                                ->startOfDay()
                                ->lt(now()->startOfDay());
                        @endphp

                        @if($terlambat)
                            <span class="badge bg-danger">Terlambat</span>
                        @else
                            <span class="badge bg-primary">Dipinjam</span>
                        @endif

                    @elseif($data->status == 'ditolak')
                        <span class="badge bg-dark">Ditolak</span>

                    @else
                        <span class="badge bg-success">Dikembalikan</span>
                    @endif

                </td>
            </tr>

            {{-- 🔥 DENDA FIX --}}
            <tr>
                <th>Denda</th>
                <td>

                    @php
                        $denda = 0;
                        $hari = 0;

                        // 🔥 masih dipinjam & terlambat
                        if ($data->status == 'dipinjam' && now()->gt($data->tgl_kembali)) {

                            $hari = \Carbon\Carbon::parse($data->tgl_kembali)
                                ->startOfDay()
                                ->diffInDays(now()->startOfDay());

                            $denda = $hari * 1000;
                        }

                        // 🔥 sudah dikembalikan & terlambat
                        elseif ($data->status == 'dikembalikan' && $data->tgl_dikembalikan > $data->tgl_kembali) {

                            $hari = \Carbon\Carbon::parse($data->tgl_kembali)
                                ->startOfDay()
                                ->diffInDays(
                                    \Carbon\Carbon::parse($data->tgl_dikembalikan)->startOfDay()
                                );

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

                        {{-- 🔥 kalau sudah dikembalikan --}}
                        @if($data->status == 'dikembalikan')
                            <br>
                            <small class="text-success">
                                ✔ Sudah Lunas
                            </small>
                        @endif

                    @else
                        <span class="text-success">Tidak ada denda</span>
                    @endif

                </td>
            </tr>

        </table>

        <a href="/kepala/peminjaman" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>

    </div>
</div>

@endsection
