@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-4">
            <i class="ti ti-book"></i> Tambah Peminjaman
        </h4>

        <form action="/petugas/peminjaman/store" method="POST">
            @csrf

            <div class="row">

                {{-- PILIH ANGGOTA --}}
                <div class="col-md-12 mb-3">
                    <label class="form-label">Pilih Anggota</label>
                    <select name="id_anggota" class="form-control" required>
                        <option value="">-- Pilih Anggota --</option>

                        @foreach($anggota as $a)
                            <option value="{{ $a->id_anggota }}">
                                {{ $a->nama }} - {{ $a->email }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- PILIH BUKU --}}
                <div class="col-md-12 mb-3">
                    <label class="form-label">Pilih Buku</label>
                    <select name="id_buku" class="form-control" required>
                        <option value="">-- Pilih Buku --</option>

                        @foreach($buku as $b)
                            <option value="{{ $b->id_buku }}">
                                {{ $b->judul }} (Stok: {{ $b->stok }})
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- TANGGAL PINJAM --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="text" class="form-control"
                        value="{{ now()->format('Y-m-d') }}" readonly>
                </div>

                {{-- TANGGAL KEMBALI --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Kembali</label>
                    <input type="text" class="form-control"
                        value="{{ now()->addDays(7)->format('Y-m-d') }}" readonly>
                </div>

            </div>

            <div class="mt-3">

                <a href="/petugas/peminjaman" class="btn btn-danger">
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
