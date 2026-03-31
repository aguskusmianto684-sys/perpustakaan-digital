@extends('layouts.kepala')

@section('content')

<div class="card">
<div class="card-body">

<h4 class="mb-4">
<i class="ti ti-user"></i> Detail Petugas
</h4>

<table class="table table-bordered">

<tr>
<th width="200">Username</th>
<td>{{ $petugas->username }}</td>
</tr>

<tr>
<th>Nama</th>
<td>{{ $petugas->nama }}</td>
</tr>

<tr>
<th>Email</th>
<td>{{ $petugas->email }}</td>
</tr>

<tr>
<th>No HP</th>
<td>{{ $petugas->no_hp }}</td>
</tr>

<tr>
<th>Jenis Kelamin</th>
<td>
@if($petugas->jenis_kel == 'L')
Laki-laki
@else
Perempuan
@endif
</td>
</tr>

<tr>
<th>Tanggal Lahir</th>
<td>{{ $petugas->tgl_lahir }}</td>
</tr>

<tr>
<th>Alamat</th>
<td>{{ $petugas->alamat }}</td>
</tr>

</table>

<a href="/kepala/petugas" class="btn btn-secondary">
<i class="ti ti-arrow-left"></i> Kembali
</a>

</div>
</div>

@endsection
