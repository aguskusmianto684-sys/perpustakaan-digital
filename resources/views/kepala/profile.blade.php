@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-kepala')
@endsection

@section('content')

<div class="container">

    {{-- tombol back --}}
    <a href="{{ url()->previous() }}" class="btn btn-outline-primary mb-3">
        <i class="ti ti-arrow-left"></i> Kembali
    </a>

    {{-- judul --}}
    <h4 class="mb-4">
        <i class="ti ti-user"></i> Profil Kepala
    </h4>

    <div class="row">

        <div class="col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    @if($kepala)

                        <table class="table table-borderless">

                            <tr>
                                <th width="150">Nama</th>
                                <td>: {{ $kepala->nama }}</td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>: {{ $kepala->email }}</td>
                            </tr>

                            <tr>
                                <th>No HP</th>
                                <td>: {{ $kepala->no_hp }}</td>
                            </tr>

                            <tr>
                                <th>Alamat</th>
                                <td>: {{ $kepala->alamat }}</td>
                            </tr>

                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>:
                                    @if($kepala->jenis_kel == 'L')
                                        Laki-laki
                                    @elseif($kepala->jenis_kel == 'P')
                                        Perempuan
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Tanggal Lahir</th>
                                <td>: {{ $kepala->tgl_lahir ?? '-' }}</td>
                            </tr>

                        </table>

                    @else

                        <div class="alert alert-warning">
                            Data kepala belum tersedia.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
