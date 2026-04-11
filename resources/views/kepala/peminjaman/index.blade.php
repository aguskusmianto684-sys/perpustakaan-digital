@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-kepala')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <h4 class="mb-3">Data Peminjaman</h4>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle datatable">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Petugas</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th width="80">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($peminjaman as $index => $p)
                            <tr>

                                <td>{{ $index + 1 }}</td>
                                <td>{{ $p->anggota->nama ?? '-' }}</td>
                                <td>{{ $p->buku->judul ?? '-' }}</td>
                                <td>{{ $p->petugas->nama ?? '-' }}</td>

                                <td>
                                    @if ($p->status == 'dipinjam')
                                        <span class="badge bg-warning">
                                            Dipinjam
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            Dikembalikan
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $denda = 0;
                                        $hari = 0;

                                        // 🔥 kalau masih dipinjam (realtime)
                                        if ($p->status == 'dipinjam' && now()->gt($p->tgl_kembali)) {
                                            $hari = \Carbon\Carbon::parse($p->tgl_kembali)
                                                ->startOfDay()
                                                ->diffInDays(now()->startOfDay());

                                            $denda = $hari * 1000;
                                        }

                                        // 🔥 kalau sudah dikembalikan (AMBIL DARI DB + HITUNG HARI)
                                        elseif (
                                            $p->status == 'dikembalikan' &&
                                            $p->tgl_dikembalikan > $p->tgl_kembali
                                        ) {
                                            $hari = \Carbon\Carbon::parse($p->tgl_kembali)
                                                ->startOfDay()
                                                ->diffInDays(\Carbon\Carbon::parse($p->tgl_dikembalikan)->startOfDay());

                                            $denda = $hari * 1000;
                                        }
                                    @endphp

                                    @if ($denda > 0)
                                        <span class="text-danger fw-semibold">
                                            Rp {{ number_format($denda) }}
                                        </span>
                                        <br>

                                        <small class="text-danger">
                                            Terlambat {{ $hari }} hari
                                        </small>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <a href="/kepala/peminjaman/detail/{{ $p->id_peminjaman }}" class="btn btn-dark btn-sm">
                                        <i class="ti ti-eye"></i>
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
