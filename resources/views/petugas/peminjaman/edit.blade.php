@extends('layouts.petugas')

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-4">
            <i class="ti ti-edit"></i> Edit Buku
        </h4>

        <form action="/petugas/buku/update/{{ $buku->id_buku }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <div class="col-md-12 mb-3">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul"
                           value="{{ $buku->judul }}"
                           class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Penulis</label>
                    <input type="text" name="penulis"
                           value="{{ $buku->penulis }}"
                           class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Penerbit</label>
                    <input type="text" name="penerbit"
                           value="{{ $buku->penerbit }}"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit"
                           value="{{ $buku->tahun_terbit }}"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori"
                           value="{{ $buku->kategori }}"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Stok Buku</label>
                    <input type="number" name="stok"
                           value="{{ $buku->stok }}"
                           class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Deskripsi Buku</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ $buku->deskripsi }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Gambar Buku</label><br>

                    @if($buku->gambar)
                        <img src="{{ asset('uploads/buku/'.$buku->gambar) }}"
                             width="90"
                             class="mb-2 rounded">
                    @endif

                    <input type="file" name="gambar" class="form-control">
                </div>

            </div>

            <div class="mt-3">

                <a href="/petugas/buku" class="btn btn-danger">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Update
                </button>

            </div>

        </form>

    </div>
</div>

@endsection
