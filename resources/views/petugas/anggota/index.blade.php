@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-3">
            <i class="ti ti-users"></i> Daftar Anggota
        </h4>

        <a href="/petugas/anggota/create" class="btn btn-primary mb-3">
            <i class="ti ti-user-plus"></i> Tambah Anggota
        </a>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle datatable">

                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No Telp</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($anggota as $index => $a)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>{{ $a->nama }}</td>

                        <td>{{ $a->email }}</td>

                        <td>{{ $a->no_hp }}</td>

                        <td>

                            <a href="/petugas/anggota/detail/{{ $a->id_anggota }}"
                               class="btn btn-dark btn-sm">
                                <i class="ti ti-eye"></i>
                            </a>

                            <a href="/petugas/anggota/edit/{{ $a->id_anggota }}"
                               class="btn btn-warning btn-sm">
                                <i class="ti ti-edit"></i>
                            </a>

                            <a href="/petugas/anggota/delete/{{ $a->id_anggota }}"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus anggota ini?')">
                                <i class="ti ti-trash"></i>
                            </a>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
</div>

@endsection
