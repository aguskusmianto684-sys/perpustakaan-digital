@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')

    <div class="container">

        <h4 class="mb-4">
            <i class="ti ti-eye"></i> Detail Peminjaman
        </h4>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 text-center">
                        <img src="{{ asset('uploads/buku/' . ($data->buku->gambar ?? 'default.png')) }}"
                            class="img-fluid rounded" style="height:200px; object-fit:cover;">
                    </div>

                    <div class="col-md-9">

                        <table class="table">

                            <tr>
                                <th>Judul Buku</th>
                                <td>{{ $data->buku->judul ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Tanggal Pinjam</th>
                                <td>{{ $data->tgl_pinjam }}</td>
                            </tr>

                            <tr>
                                <th>Tanggal Kembali</th>
                                <td>{{ $data->tgl_kembali }}</td>
                            </tr>

                            {{-- STATUS --}}
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($data->status == 'dipinjam')
                                        <span class="badge bg-primary">Dipinjam</span>
                                    @elseif($data->status == 'menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($data->status == 'menunggu pengembalian')
                                        <span class="badge bg-info">Menunggu Konfirmasi</span>
                                    @elseif($data->status == 'ditolak')
                                        <span class="badge bg-dark">Ditolak</span>
                                    @elseif($data->status == 'dikembalikan')
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                            </tr>

                            {{--bagian denda --}}
                            <tr>
                                <th>Denda</th>
                                <td>

                                    @php
                                        $dendaAsli = $data->pengembalian->denda ?? 0;
                                    @endphp

                                    {{-- SUDAH DIKEMBALIKAN --}}
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
                                                Tidak ada denda (Tepat Waktu)
                                            </span>
                                        @endif

                                    {{-- MASIH DIPINJAM & TELAT --}}
                                    @elseif ($data->status == 'dipinjam' && now()->gt($data->tgl_kembali))

                                        @php
                                            $hari = \Carbon\Carbon::parse($data->tgl_kembali)
                                                ->startOfDay()
                                                ->diffInDays(now()->startOfDay());

                                            $denda = $hari * 1000;
                                        @endphp

                                        <span class="text-danger fw-semibold">
                                            Rp {{ number_format($denda) }}
                                        </span>
                                        <br>
                                        <small class="text-danger">
                                            Terlambat {{ $hari }} hari
                                        </small>

                                    @else
                                        <span class="text-success">
                                            Tidak ada denda
                                        </span>
                                    @endif

                                </td>
                            </tr>

                        </table>

                        <a href="/anggota/riwayat" class="btn btn-secondary">
                            ← Kembali
                        </a>

                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection
