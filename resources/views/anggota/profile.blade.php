@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')

<div class="container">
    
    <a href="{{ url()->previous() }}" class="btn btn-outline-primary mb-3">
        <i class="ti ti-arrow-left"></i> Kembali
    </a>

    {{-- judul halaman --}}
    <h4 class="mb-4">
        <i class="ti ti-user"></i> Profil Saya
    </h4>

    <div class="row">

        <div class="col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    @if($anggota)

                        <table class="table table-borderless">

                            <tr>
                                <th width="150">Nama</th>
                                <td>: {{ $anggota->nama }}</td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>: {{ $anggota->email }}</td>
                            </tr>

                            <tr>
                                <th>No HP</th>
                                <td>: {{ $anggota->no_hp }}</td>
                            </tr>

                            <tr>
                                <th>Alamat</th>
                                <td>: {{ $anggota->alamat }}</td>
                            </tr>

                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>:
                                    @if($anggota->jenis_kel == 'L')
                                        Laki-laki
                                    @elseif($anggota->jenis_kel == 'P')
                                        Perempuan
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Tanggal Lahir</th>
                                <td>: {{ $anggota->tgl_lahir ?? '-' }}</td>
                            </tr>

                        </table>

                    @else

                        <div class="alert alert-warning">
                            Data profil belum tersedia.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
