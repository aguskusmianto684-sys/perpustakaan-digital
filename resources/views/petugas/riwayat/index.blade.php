@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <h4 class="mb-3">
                <i class="ti ti-history"></i> Riwayat Peminjaman
            </h4>

            <div class="table-responsive">

                <table id="laporanTable" class="table table-bordered table-hover align-middle">

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

                        @foreach ($data as $index => $d)
                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>{{ $d->anggota->nama ?? '-' }}</td>
                                <td>{{ $d->buku->judul ?? '-' }}</td>

                                <td>{{ $d->tgl_pinjam }}</td>

                                <td>
                                    {{ $d->tgl_dikembalikan ?? '-' }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $d->tgl_kembali }}
                                    </small>
                                </td>

                                {{-- DENDA --}}
                                <td>
                                    @php
                                        $dendaAsli = $d->pengembalian->denda ?? 0;
                                    @endphp

                                    @if ($d->status == 'dikembalikan')
                                        @if ($dendaAsli > 0)
                                            <span class="text-danger">
                                                Rp {{ number_format($dendaAsli) }}
                                            </span>
                                            <br>

                                            @if ($d->denda == 0)
                                                <small class="text-success">
                                                    Sudah lunas
                                                </small>
                                            @else
                                                <small class="text-warning">
                                                    Belum dibayar
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

                                {{-- STATUS --}}
                                <td>

                                    @if ($d->status == 'ditolak')
                                        <span class="badge bg-dark">Ditolak</span>
                                    @elseif($d->status == 'dikembalikan')
                                        @if ($d->denda > 0)
                                            <span class="badge bg-danger">Terlambat</span>
                                            <br>
                                            <small class="text-danger">Belum dibayar</small>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                            <br>
                                            <small class="text-success">Sudah lunas</small>
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
