@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection
@section('content')

<div class="card">
    <div class="card-body">

        {{-- judul --}}
        <h4 class="mb-3">
            <i class="ti ti-history"></i> Riwayat Peminjaman
        </h4>

        <div class="table-responsive">

            <table id="laporanTable" class="table table-bordered table-hover align-middle">

                {{-- header --}}
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($data as $index => $d)

                    @php
                        $denda = 0;
                        $terlambat = 0;

                        if($d->tgl_dikembalikan && $d->tgl_dikembalikan > $d->tgl_kembali){
                            $terlambat = \Carbon\Carbon::parse($d->tgl_kembali)
                                ->diffInDays($d->tgl_dikembalikan);

                            $denda = $terlambat * 1000;
                        }
                    @endphp

                    <tr>

                        {{-- nomor --}}
                        <td>{{ $index + 1 }}</td>

                        {{-- ✅ RELASI --}}
                        <td>{{ $d->anggota->nama ?? '-' }}</td>
                        <td>{{ $d->buku->judul ?? '-' }}</td>

                        {{-- tanggal pinjam --}}
                        <td>{{ $d->tgl_pinjam }}</td>

                        {{-- tanggal kembali --}}
                        <td>
                            {{ $d->tgl_dikembalikan ?? '-' }}
                            <br>
                            <small class="text-muted">
                                {{ $d->tgl_kembali }}
                            </small>
                        </td>

                        {{-- denda --}}
                        <td>
                            @if($denda > 0)
                                <span class="text-danger">
                                    Rp {{ number_format($denda) }}
                                </span>
                                <br>
                                <small class="text-danger">
                                    Terlambat {{ $terlambat }} hari
                                </small>
                            @else
                                -
                            @endif
                        </td>

                        {{-- status --}}
                        <td>

                            @if($d->status == 'ditolak')
                                <span class="badge bg-dark">Ditolak</span>

                            @elseif($d->status == 'dikembalikan')

                                @if($denda > 0)
                                    <span class="badge bg-danger">Terlambat</span>
                                    <br>
                                    <small class="text-danger">Sudah dikembalikan</small>
                                @else
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @endif

                            @else
                                <span class="badge bg-secondary">Belum Selesai</span>
                            @endif

                        </td>

                        <td>
                            <a href="{{ route('petugas.riwayat.detail', $d->id_peminjaman) }}"
                            class="btn btn-sm btn-info">
                                <i class="ti ti-eye"></i>
                            </a>
                        </td>


                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
</div>

@endsection
