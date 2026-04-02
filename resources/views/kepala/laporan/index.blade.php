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

                        <td>{{ $d->anggota }}</td>

                        <td>{{ $d->buku }}</td>

                        <td>{{ $d->petugas }}</td>

                        <td>
                            <span class="badge bg-success">
                                {{ $d->status }}
                            </span>
                        </td>

                        <td>
                            @if($d->denda > 0)
                                <span class="text-danger">
                                    Rp {{ number_format($d->denda) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ $d->tgl_pinjam }}</td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
</div>

@endsection
