@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-4">
            <i class="ti ti-book"></i> Detail Buku
        </h4>

        <div class="row">

            <div class="col-md-4">
                <img src="{{ asset('uploads/buku/'.$buku->gambar) }}"
                     class="img-fluid rounded">
            </div>

            <div class="col-md-8">

                <table class="table">

                    <tr>
                        <th width="200">Judul Buku</th>
                        <td>{{ $buku->judul }}</td>
                    </tr>

                    <tr>
                        <th>Penulis</th>
                        <td>{{ $buku->penulis }}</td>
                    </tr>

                    <tr>
                        <th>Penerbit</th>
                        <td>{{ $buku->penerbit }}</td>
                    </tr>

                    <tr>
                        <th>Tahun Terbit</th>
                        <td>{{ $buku->tahun_terbit }}</td>
                    </tr>

                    <tr>
                        <th>Kategori</th>
                        <td>{{ $buku->kategori }}</td>
                    </tr>

                    <tr>
                        <th>Stok</th>
                        <td>{{ $buku->stok }}</td>
                    </tr>

                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $buku->deskripsi }}</td>
                    </tr>

                </table>

                <a href="/petugas/buku" class="btn btn-secondary">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>

            </div>

        </div>

    </div>
</div>

@endsection
