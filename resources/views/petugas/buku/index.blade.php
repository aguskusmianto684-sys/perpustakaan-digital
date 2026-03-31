@extends('layouts.petugas')

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-3">Daftar Buku</h4>

        <a href="/petugas/buku/create" class="btn btn-primary mb-3">
            + Tambah Buku
        </a>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle datatable">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Terbit</th>
                        <th>Kategori</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($buku as $index => $b)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            <img src="{{ asset('uploads/buku/'.$b->gambar) }}"
                                 width="50"
                                 style="border-radius:5px;">
                        </td>

                        <td>{{ $b->judul }}</td>

                        <td>{{ $b->penulis }}</td>

                        <td>{{ $b->tahun_terbit }}</td>

                        <td>{{ $b->kategori }}</td>

                        <td>

                            <a href="/petugas/buku/detail/{{ $b->id_buku }}"
                               class="btn btn-dark btn-sm">
                                <i class="ti ti-eye"></i>
                            </a>

                            <a href="/petugas/buku/edit/{{ $b->id_buku }}"
                               class="btn btn-warning btn-sm">
                                <i class="ti ti-edit"></i>
                            </a>

                            <a href="/petugas/buku/delete/{{ $b->id_buku }}"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus buku ini?')">
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
