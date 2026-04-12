@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-kepala')
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <h4 class="mb-4">
                <i class="ti ti-user-plus"></i> Tambah Petugas
            </h4>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/kepala/petugas/store" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>No HP</label>
                        <input type="text" name="no_hp" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kel" class="form-control" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" class="form-control" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" required></textarea>
                    </div>

                </div>

                <button class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan
                </button>

                <a href="/kepala/petugas" class="btn btn-secondary">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>

            </form>

        </div>
    </div>
@endsection
