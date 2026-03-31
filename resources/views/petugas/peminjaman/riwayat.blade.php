@extends('layouts.petugas')

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-3">
            <i class="ti ti-history"></i> Riwayat Peminjaman
        </h4>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle datatable">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($data as $index => $d)

                    <tr>

                        <td>{{ $index + 1 }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->judul }}</td>
                        <td>{{ $d->tgl_pinjam }}</td>
                        <td>{{ $d->tgl_kembali }}</td>

                        <td>
                            <span class="badge bg-success">
                                Dikembalikan
                            </span>
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
</div>

@endsection
