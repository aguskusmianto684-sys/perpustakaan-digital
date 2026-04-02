@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')

<div class="row">
  <div class="col-lg-4">
    <div class="card p-4">
      <h5>Total Buku</h5>
      <h3>0</h3>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card p-4">
      <h5>Total Anggota</h5>
      <h3>0</h3>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card p-4">
      <h5>Peminjaman Aktif</h5>
      <h3>0</h3>
    </div>
  </div>
</div>

@endsection
