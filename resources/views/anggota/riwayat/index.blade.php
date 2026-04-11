@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection
@section('content')
    <div class="container">

        {{-- judul halaman --}}
        <h4 class="mb-4">
            <i class="ti ti-book"></i> Buku Saya
        </h4>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle datatable">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($data as $index => $d)
                        <tr>

                            {{-- nomor --}}
                            <td>{{ $index + 1 }}</td>

                            {{-- ✅ RELASI BUKU --}}
                            <td>
                                <img src="{{ asset('uploads/buku/' . ($d->buku->gambar ?? 'default.png')) }}" width="40">
                                {{ $d->buku->judul ?? '-' }}
                            </td>

                            {{-- tanggal --}}
                            <td>{{ $d->tgl_pinjam }}</td>
                            <td>{{ $d->tgl_kembali }}</td>

                            {{-- denda --}}
                            <td>
                                @php
                                    $denda = 0;
                                    $hari = 0;

                                    // 🔥 kalau sudah dikembalikan
                                    if ($d->status == 'dikembalikan' && $d->tgl_dikembalikan) {
                                        if ($d->tgl_dikembalikan > $d->tgl_kembali) {
                                            $hari = \Carbon\Carbon::parse($d->tgl_kembali)
                                                ->startOfDay()
                                                ->diffInDays(\Carbon\Carbon::parse($d->tgl_dikembalikan)->startOfDay());

                                            $denda = $hari * 1000;
                                        }
                                    }
                                    // 🔥 kalau masih dipinjam (realtime)
                                    elseif ($d->status == 'dipinjam' && now()->gt($d->tgl_kembali)) {
                                        $hari = \Carbon\Carbon::parse($d->tgl_kembali)
                                            ->startOfDay()
                                            ->diffInDays(now()->startOfDay());

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

                            {{-- status --}}
                            <td>

                                {{-- terlambat --}}
                                @if ($d->status == 'dipinjam' && now()->gt($d->tgl_kembali))
                                    <span class="badge bg-danger">Terlambat</span>

                                    {{-- dipinjam --}}
                                @elseif($d->status == 'dipinjam')
                                    <span class="badge bg-primary">Dipinjam</span>

                                    {{-- menunggu --}}
                                @elseif($d->status == 'menunggu')
                                    <span class="badge bg-warning text-dark">Menunggu</span>

                                    {{-- ditolak --}}
                                @elseif($d->status == 'ditolak')
                                    <span class="badge bg-dark">Ditolak</span>

                                    {{-- dikembalikan --}}
                                @elseif($d->status == 'dikembalikan')
                                    <span class="badge bg-success">Dikembalikan</span>

                                    {{-- fallback --}}
                                @else
                                    <span class="badge bg-secondary">Tidak diketahui</span>
                                @endif

                            </td>

                            <td>
                                <a href="/anggota/riwayat/detail/{{ $d->id_peminjaman }}" class="btn btn-sm btn-info"
                                    title="Detail">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
@endsection
