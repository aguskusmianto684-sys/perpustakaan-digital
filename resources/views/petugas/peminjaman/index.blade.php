@extends('layouts.petugas')

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="mb-3">
            <i class="ti ti-book"></i> Data Peminjaman
        </h4>

        <a href="/petugas/peminjaman/create" class="btn btn-primary mb-3">
            + Tambah Peminjaman
        </a>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle datatable">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($peminjaman as $index => $p)

                    <tr>

                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->judul }}</td>
                        <td>{{ $p->tgl_pinjam }}</td>
                        <td>{{ $p->tgl_kembali }}</td>

                        <td>

                            @php
                                $denda = 0;

                                if ($p->status == 'dipinjam' && now()->gt($p->tgl_kembali)) {

                                    $hari = \Carbon\Carbon::parse($p->tgl_kembali)
                                            ->diffInDays(now());

                                    $denda = $hari * 1000;
                                }
                            @endphp

                            Rp {{ number_format($denda) }}

                        </td>

                        <td>

                            @if($p->status == 'dipinjam' && now()->gt($p->tgl_kembali))
                                <span class="badge bg-danger">Terlambat</span>

                            @elseif($p->status == 'dipinjam')
                                <span class="badge bg-primary">Dipinjam</span>

                            @elseif($p->status == 'menunggu')
                                <span class="badge bg-warning">Menunggu</span>

                            @else
                                <span class="badge bg-success">Dikembalikan</span>
                            @endif

                        </td>

                        <td>

                            @if($p->status == 'menunggu')

                                <a href="/petugas/peminjaman/konfirmasi/{{ $p->id_peminjaman }}"
                                   class="btn btn-primary btn-sm">
                                    Konfirmasi
                                </a>

                            @elseif($p->status == 'dipinjam')

                                <a href="/petugas/peminjaman/kembalikan/{{ $p->id_peminjaman }}"
                                   class="btn btn-success btn-sm">
                                    Dikembalikan
                                </a>

                            @else

                                <span class="badge bg-success">Selesai</span>

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
