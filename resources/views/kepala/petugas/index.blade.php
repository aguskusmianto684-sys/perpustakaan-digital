@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-kepala')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <h4 class="mb-3">Data Petugas</h4>

            <a href="/kepala/petugas/create" class="btn btn-primary">
                + Petugas
            </a>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle datatable">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($petugas as $index => $p)
                            <tr>

                                <td>{{ $index + 1 }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->email }}</td>
                                <td>{{ $p->jenis_kel }}</td>

                                <td>

                                    @if ($p->status == 'aktif')
                                        <span class="badge bg-success">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Nonaktif
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <a href="/kepala/petugas/detail/{{ $p->id_petugas }}" class="btn btn-dark btn-sm">
                                        <i class="ti ti-eye"></i>
                                    </a>

                                    <a href="/kepala/petugas/edit/{{ $p->id_petugas }}" class="btn btn-warning btn-sm">
                                        <i class="ti ti-edit"></i>
                                    </a>

                                    @if ($p->status == 'aktif')
                                        <a href="/kepala/petugas/nonaktif/{{ $p->id_petugas }}"
                                            class="btn btn-secondary btn-sm"
                                            onclick="return confirm('Nonaktifkan petugas ini?')">
                                            <i class="ti ti-user-off"></i>
                                        </a>
                                    @else
                                        <a href="/kepala/petugas/aktif/{{ $p->id_petugas }}" class="btn btn-success btn-sm"
                                            onclick="return confirm('Aktifkan kembali petugas ini?')">
                                            <i class="ti ti-user-check"></i>
                                        </a>
                                    @endif

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>
@endsection
