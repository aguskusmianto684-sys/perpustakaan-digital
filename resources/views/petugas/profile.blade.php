@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')

    <div class="container">

        <a href="{{ url()->previous() }}" class="btn btn-outline-primary mb-3">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>

        {{-- judul --}}
        <h4 class="mb-4">
            <i class="ti ti-user"></i> Profil Saya
        </h4>

        <div class="row">

            <div class="col-md-6">

                <div class="card shadow-sm">

                    <div class="card-body">

                        @if ($petugas)
                            <table class="table table-borderless">

                                <tr>
                                    <th width="150">Nama</th>
                                    <td>: {{ $petugas->nama }}</td>
                                </tr>

                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $petugas->email }}</td>
                                </tr>

                                <tr>
                                    <th>No HP</th>
                                    <td>: {{ $petugas->no_hp }}</td>
                                </tr>

                                <tr>
                                    <th>Alamat</th>
                                    <td>: {{ $petugas->alamat }}</td>
                                </tr>

                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>:
                                        @if ($petugas->jenis_kel == 'L')
                                            Laki-laki
                                        @elseif($petugas->jenis_kel == 'P')
                                            Perempuan
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>: {{ $petugas->tgl_lahir ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <th>Status</th>
                                    <td>:
                                        @if ($petugas->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>

                            </table>
                        @else
                            <div class="alert alert-warning">
                                Data profil tidak ditemukan.
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
