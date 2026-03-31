@extends('layouts.petugas')

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-4">
            <i class="ti ti-user"></i> Detail Anggota
        </h4>

        <table class="table table-bordered">

            <tr>
                <th width="200">Nama</th>
                <td>{{ $anggota->nama }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $anggota->email }}</td>
            </tr>

            <tr>
                <th>No HP</th>
                <td>{{ $anggota->no_hp }}</td>
            </tr>

            <tr>
                <th>Jenis Kelamin</th>
                <td>
                    @if($anggota->jenis_kel == 'L')
                        Laki-laki
                    @else
                        Perempuan
                    @endif
                </td>
            </tr>

            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ $anggota->tgl_lahir }}</td>
            </tr>

            <tr>
                <th>Alamat</th>
                <td>{{ $anggota->alamat }}</td>
            </tr>

        </table>

        <a href="/petugas/anggota" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>

    </div>
</div>

@endsection
