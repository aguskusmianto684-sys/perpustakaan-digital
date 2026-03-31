@extends('layouts.anggota')

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
                </tr>
            </thead>

            <tbody>

                @foreach($data as $index => $d)

                <tr>

                    <td>{{ $index + 1 }}</td>

                    <td>
                        <img src="{{ asset('uploads/buku/'.$d->gambar) }}" width="40">
                        {{ $d->judul }}
                    </td>

                    <td>{{ $d->tgl_pinjam }}</td>

                    <td>{{ $d->tgl_kembali }}</td>

                    <td>
                        @if($d->denda > 0)
                            <span class="text-danger">
                                Rp {{ number_format($d->denda) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        @if($d->status == 'menunggu')
                            <span class="badge bg-warning">Menunggu</span>

                        @elseif($d->status == 'dipinjam')
                            <span class="badge bg-primary">Dipinjam</span>

                        @else
                            <span class="badge bg-success">Dikembalikan</span>
                        @endif
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
