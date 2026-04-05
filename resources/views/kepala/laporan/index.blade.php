@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-kepala')
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-3">Laporan Peminjaman</h4>

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
                    </tr>
                </thead>

                <tbody>

                    @foreach($data as $index => $d)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        {{-- ✅ RELASI --}}
                        <td>{{ $d->anggota->nama ?? '-' }}</td>
                        <td>{{ $d->buku->judul ?? '-' }}</td>
                        <td>{{ $d->petugas->nama ?? '-' }}</td>

                        {{-- status --}}
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

                        {{-- tanggal --}}
                        <td>{{ $d->tgl_pinjam }}</td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
</div>

@endsection
