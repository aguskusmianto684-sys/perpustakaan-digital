@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <h4 class="mb-4">
                <i class="ti ti-book"></i> Tambah Buku
            </h4>

            <form action="/petugas/buku/store" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" placeholder="Masukkan judul buku"
                            required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="penulis" class="form-control" placeholder="Nama penulis">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" placeholder="Nama penerbit">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" class="form-control" placeholder="Contoh: 2005">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Novel">Novel</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Komik">Komik</option>
                            <option value="Sejarah">Sejarah</option>
                            <option value="Teknologi">Teknologi</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Buku</label>
                        <input type="number" name="stok" class="form-control" placeholder="Jumlah stok">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Deskripsi Buku</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Gambar Buku</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>

                </div>

                <div class="mt-3">

                    <a href="/petugas/buku" class="btn btn-danger">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy"></i> Simpan
                    </button>

                </div>

            </form>

        </div>
    </div>
@endsection
