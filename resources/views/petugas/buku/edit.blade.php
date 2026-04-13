@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

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
                        <input type="text" name="judul" value="{{ $buku->judul }}" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="penulis" value="{{ $buku->penulis }}" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" value="{{ $buku->penerbit }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" value="{{ $buku->tahun_terbit }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori</label>

                        <select name="kategori" class="form-control select2" required>
                            <option value="">-- Pilih Kategori --</option>

                            <option value="Novel" {{ $buku->kategori == 'Novel' ? 'selected' : '' }}>Novel</option>
                            <option value="Cerpen" {{ $buku->kategori == 'Cerpen' ? 'selected' : '' }}>Cerpen</option>
                            <option value="Komik" {{ $buku->kategori == 'Komik' ? 'selected' : '' }}>Komik</option>
                            <option value="Biografi" {{ $buku->kategori == 'Biografi' ? 'selected' : '' }}>Biografi</option>
                            <option value="Sejarah" {{ $buku->kategori == 'Sejarah' ? 'selected' : '' }}>Sejarah</option>
                            <option value="Pendidikan" {{ $buku->kategori == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="Sains" {{ $buku->kategori == 'Sains' ? 'selected' : '' }}>Sains</option>
                            <option value="Teknologi" {{ $buku->kategori == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                            <option value="Agama" {{ $buku->kategori == 'Agama' ? 'selected' : '' }}>Agama</option>
                            <option value="Kesehatan" {{ $buku->kategori == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                            <option value="Ekonomi" {{ $buku->kategori == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                            <option value="Bisnis" {{ $buku->kategori == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                            <option value="Motivasi" {{ $buku->kategori == 'Motivasi' ? 'selected' : '' }}>Motivasi</option>
                            <option value="Psikologi" {{ $buku->kategori == 'Psikologi' ? 'selected' : '' }}>Psikologi</option>
                            <option value="Fiksi" {{ $buku->kategori == 'Fiksi' ? 'selected' : '' }}>Fiksi</option>
                            <option value="Non-Fiksi" {{ $buku->kategori == 'Non-Fiksi' ? 'selected' : '' }}>Non-Fiksi</option>
                            <option value="Anak-anak" {{ $buku->kategori == 'Anak-anak' ? 'selected' : '' }}>Anak-anak</option>
                            <option value="Remaja" {{ $buku->kategori == 'Remaja' ? 'selected' : '' }}>Remaja</option>
                            <option value="Ensiklopedia" {{ $buku->kategori == 'Ensiklopedia' ? 'selected' : '' }}>Ensiklopedia</option>
                            <option value="Bahasa" {{ $buku->kategori == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Buku</label>
                        <input type="number" name="stok" value="{{ $buku->stok }}" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Deskripsi Buku</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ $buku->deskripsi }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Gambar Buku</label><br>

                        @if ($buku->gambar)
                            <img src="{{ asset('uploads/buku/' . $buku->gambar) }}" width="90" class="mb-2 rounded">
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


@push('js')
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "-- Pilih Kategori --",
            allowClear: true
        });
    });
</script>
@endpush
