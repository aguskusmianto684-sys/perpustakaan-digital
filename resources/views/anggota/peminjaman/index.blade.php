@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')
    <div class="container">

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
                        <th width="160">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($data as $index => $d)

                        @if(in_array($d->status, ['menunggu', 'dipinjam', 'menunggu pengembalian']))

                        <tr>

                            <td>{{ $index + 1 }}</td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('uploads/buku/' . $d->gambar) }}" width="40"
                                        style="border-radius:6px">
                                    <span>{{ $d->judul }}</span>
                                </div>
                            </td>

                            <td>{{ $d->tgl_pinjam }}</td>
                            <td>{{ $d->tgl_kembali }}</td>

                            <td>-</td>

                            <td>

                                @if($d->status == 'dipinjam')
                                    <span class="badge bg-primary">Dipinjam</span>

                                    {{-- 🔥 JIKA PENGEMBALIAN DITOLAK --}}
                                    @if($d->alasan)
                                        <br>
                                        <small class="text-danger">
                                            Pengembalian ditolak: {{ $d->alasan }}
                                        </small>
                                    @endif

                                @elseif($d->status == 'menunggu')
                                    <span class="badge bg-warning text-dark">Menunggu</span>

                                @elseif($d->status == 'menunggu pengembalian')
                                    <span class="badge bg-info">Menunggu Konfirmasi</span>
                                @endif

                            </td>

                            <td class="text-center">

                                @if ($d->status == 'dipinjam')
                                    <a href="/anggota/pengembalian/{{ $d->id_peminjaman }}"
                                        class="btn btn-sm btn-warning"
                                        onclick="return confirm('Ajukan pengembalian buku ini?')">
                                        Ajukan
                                    </a>

                                @elseif($d->status == 'menunggu pengembalian')
                                    <span class="badge bg-info">Menunggu</span>

                                @else
                                    <span class="text-muted">-</span>
                                @endif

                            </td>

                        </tr>

                        @endif

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
@endsection
