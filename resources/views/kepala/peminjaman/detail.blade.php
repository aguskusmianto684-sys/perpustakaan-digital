@extends('layouts.kepala')

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-4">
            <i class="ti ti-book"></i> Detail Peminjaman
        </h4>

        <table class="table table-bordered">

            <tr>
                <th width="200">Anggota</th>
                <td>{{ $data->anggota }}</td>
            </tr>

            <tr>
                <th>Buku</th>
                <td>{{ $data->buku }}</td>
            </tr>

            <tr>
                <th>Petugas</th>
                <td>
                    @if($data->petugas)
                        {{ $data->petugas }}
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
                <th>Tanggal Kembali</th>
                <td>{{ $data->tgl_kembali }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>
                    @if($data->status == 'menunggu')
                        <span class="badge bg-warning">Menunggu</span>

                    @elseif($data->status == 'dipinjam')
                        <span class="badge bg-primary">Dipinjam</span>

                    @else
                        <span class="badge bg-success">Dikembalikan</span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Denda</th>
                <td>
                    @if($data->denda > 0)
                        <span class="text-danger">
                            Rp {{ number_format($data->denda) }}
                        </span>
                    @else
                        -
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
