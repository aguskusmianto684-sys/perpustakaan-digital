@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')

<div class="container">

    <h4 class="mb-4">
        <i class="ti ti-history"></i> Riwayat Peminjaman
    </h4>

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle datatable">

            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @foreach($data as $index => $d)

                <tr>

                    {{-- nomor --}}
                    <td>{{ $index + 1 }}</td>

                    {{-- buku --}}
                    <td>
                        <img src="{{ asset('uploads/buku/'.$d->gambar) }}" width="40">
                        {{ $d->judul }}
                    </td>

                    {{-- tanggal pinjam --}}
                    <td>{{ $d->tgl_pinjam }}</td>

                    {{-- tanggal kembali --}}
                    <td>

                        {{-- tanggal real --}}
                        @if($d->tgl_dikembalikan)
                            {{ $d->tgl_dikembalikan }}
                        @else
                            -
                        @endif

                        {{-- deadline --}}
                        <br>
                        <small class="text-muted">
                            Deadline: {{ $d->tgl_kembali }}
                        </small>

                    </td>

                    {{-- status --}}
                    <td>

                        {{-- ditolak --}}
                        @if($d->status == 'ditolak')
                            <span class="badge bg-dark">Ditolak</span>

                        {{-- terlambat --}}
                        @elseif($d->status == 'dipinjam' && now()->gt($d->tgl_kembali))
                            <span class="badge bg-danger">Terlambat</span>

                        {{-- dipinjam --}}
                        @elseif($d->status == 'dipinjam')
                            <span class="badge bg-primary">Dipinjam</span>

                        {{-- menunggu --}}
                        @elseif($d->status == 'menunggu')
                            <span class="badge bg-warning text-dark">Menunggu</span>

                        {{-- dikembalikan --}}
                        @elseif($d->status == 'dikembalikan')
                            <span class="badge bg-success">Dikembalikan</span>

                        {{-- fallback --}}
                        @else
                            <span class="badge bg-secondary">Tidak diketahui</span>

                        @endif

                    </td>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
