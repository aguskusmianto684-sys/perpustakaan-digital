@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-kepala')
@endsection

@section('content')

<div class="card">
<div class="card-body">

<h4 class="mb-4">
    <i class="ti ti-edit"></i> Edit Petugas
</h4>

<form action="/kepala/petugas/update/{{ $petugas->id_petugas }}" method="POST">
@csrf

<div class="row">

    {{-- Username --}}
    <div class="col-md-6 mb-3">
        <label>Username</label>
        <input type="text" name="username"
               value="{{ $petugas->username }}"
               class="form-control" required>
    </div>

    {{-- Password opsional --}}
    <div class="col-md-6 mb-3">
        <label>Password (Opsional)</label>

        <div style="position: relative;">
            <input type="password" name="password" id="password"
                class="form-control"
                placeholder="Kosongkan jika tidak diubah">

            <span onclick="togglePassword()"
                style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer;">
                👁️
            </span>
        </div>

    </div>

    {{-- Nama --}}
    <div class="col-md-6 mb-3">
        <label>Nama</label>
        <input type="text" name="nama"
               value="{{ $petugas->nama }}"
               class="form-control" required>
    </div>

    {{-- Email --}}
    <div class="col-md-6 mb-3">
        <label>Email</label>
        <input type="email" name="email"
               value="{{ $petugas->email }}"
               class="form-control">
    </div>

    {{-- No HP --}}
    <div class="col-md-6 mb-3">
        <label>No HP</label>
        <input type="text" name="no_hp"
               value="{{ $petugas->no_hp }}"
               class="form-control">
    </div>

    {{-- Jenis Kelamin --}}
    <div class="col-md-6 mb-3">
        <label>Jenis Kelamin</label>
        <select name="jenis_kel" class="form-control">
            <option value="L" {{ $petugas->jenis_kel == 'L' ? 'selected' : '' }}>
                Laki-laki
            </option>
            <option value="P" {{ $petugas->jenis_kel == 'P' ? 'selected' : '' }}>
                Perempuan
            </option>
        </select>
    </div>

    {{-- Tanggal Lahir --}}
    <div class="col-md-6 mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir"
               value="{{ $petugas->tgl_lahir }}"
               class="form-control">
    </div>

    {{-- Alamat --}}
    <div class="col-md-12 mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control">{{ $petugas->alamat }}</textarea>
    </div>

</div>

{{-- Tombol --}}
<button class="btn btn-primary">
    <i class="ti ti-device-floppy"></i> Update
</button>

<a href="/kepala/petugas" class="btn btn-secondary">
    <i class="ti ti-arrow-left"></i> Kembali
</a>

</form>

</div>
</div>

<script>
function togglePassword() {
    var input = document.getElementById("password");

    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>

@endsection
