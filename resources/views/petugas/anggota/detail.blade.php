@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            {{-- judul --}}
            <h4 class="mb-4">
                <i class="ti ti-user"></i> Detail Anggota
            </h4>

            {{-- data anggota --}}
            <table class="table table-bordered">

                <tr>
                    <th width="200">Nama</th>
                    <td>{{ $anggota->nama }}</td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td>{{ $anggota->email }}</td>
                </tr>

                <tr>
                    <th>No HP</th>
                    <td>{{ $anggota->no_hp }}</td>
                </tr>

                <tr>
                    <th>Jenis Kelamin</th>
                    <td>
                        @if ($anggota->jenis_kel == 'L')
                            Laki-laki
                        @else
                            Perempuan
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Tanggal Lahir</th>
                    <td>{{ $anggota->tgl_lahir }}</td>
                </tr>

                <tr>
                    <th>Alamat</th>
                    <td>{{ $anggota->alamat }}</td>
                </tr>

            </table>

            {{-- tombol kembali --}}
            <a href="/petugas/anggota" class="btn btn-secondary mb-4">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>

            {{-- ====================== --}}
            {{-- BUKU YANG SEDANG DIPINJAM --}}
            {{-- ====================== --}}
            <h5 class="mb-3">
                Buku Yang Sedang Dipinjam
            </h5>

            {{-- jumlah buku aktif --}}
            <p class="text-muted">
                Total: {{ $peminjaman->count() }} buku
            </p>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    {{-- header --}}
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Deadline</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($peminjaman as $index => $p)
                            <tr>

                                {{-- nomor --}}
                                <td>{{ $index + 1 }}</td>

                                {{-- buku --}}
                                <td>
                                    <img src="{{ asset('uploads/buku/' . ($p->buku->gambar ?? 'default.png')) }}"
                                        width="40">
                                    {{ $p->buku->judul ?? '-' }}
                                </td>

                                {{-- tanggal pinjam --}}
                                <td>{{ $p->tgl_pinjam }}</td>

                                {{-- deadline --}}
                                <td>
                                    {{ $p->tgl_kembali }}
                                </td>

                                {{-- status --}}
                                <td>

                                    {{-- cek terlambat --}}
                                    @if (now()->gt($p->tgl_kembali))
                                        <span class="badge bg-danger">Terlambat</span>
                                    @else
                                        <span class="badge bg-primary">Dipinjam</span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            {{-- jika tidak ada buku aktif --}}
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Tidak ada buku yang sedang dipinjam
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>
@endsection
