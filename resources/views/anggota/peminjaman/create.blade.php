@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')

<div class="container">

    <h4 class="mb-4">Form Pinjam Buku</h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="/anggota/pinjam/store" method="POST">
                @csrf

                <input type="hidden" name="id_buku" value="{{ $buku->id_buku }}">

                {{-- Nama peminjam --}}
                <div class="mb-3">
                    <label class="form-label">Nama Peminjam</label>
                    <input type="text" class="form-control"
                           value="{{ $user->username }}" readonly>
                </div>

                {{-- Buku --}}
                <div class="mb-3">
                    <label class="form-label">Buku Yang Akan Dipinjam</label>
                    <input type="text" class="form-control"
                           value="{{ $buku->judul }} - stok {{ $buku->stok }}" readonly>
                </div>

                {{-- Tanggal pinjam --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" class="form-control"
                           value="{{ date('Y-m-d') }}" readonly>
                </div>

                {{-- Tanggal kembali --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal Kembali</label>
                    <input type="date" class="form-control"
                           value="{{ date('Y-m-d', strtotime('+7 days')) }}" readonly>
                </div>

                <div class="text-end mt-4">

                    <a href="/anggota/buku" class="btn btn-warning">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
