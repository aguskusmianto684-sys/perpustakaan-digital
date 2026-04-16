@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')
<div class="container">

    <h4 class="mb-4">
        <i class="ti ti-book"></i> Riwayat peminjaman
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

                    <td>{{ $index + 1 }}</td>

                    <td>
                        <img src="{{ asset('uploads/buku/' . ($d->buku->gambar ?? 'default.png')) }}" width="40">
                        {{ $d->buku->judul ?? '-' }}
                    </td>

                    <td>{{ $d->tgl_pinjam }}</td>
                    <td>{{ $d->tgl_kembali }}</td>

                    <td>
                        @php
                            $dendaAsli = $d->pengembalian->denda ?? 0;
                        @endphp

                        @if ($d->status == 'dikembalikan')
                            @if ($dendaAsli > 0)
                                Rp {{ number_format($dendaAsli) }}
                            @else
                                <small class="text-success">Tepat Waktu</small>
                            @endif
                        @else
                            -
                        @endif
                    </td>

                    <td>

                        @if($d->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>

                            @if($d->alasan)
                                <br>
                                <button class="btn btn-sm btn-outline-danger mt-1"
                                    onclick="showAlasan(this)"
                                    data-alasan="{{ $d->alasan }}">
                                    Lihat Alasan
                                </button>
                            @endif

                        @elseif($d->status == 'dikembalikan')
                            <span class="badge bg-success">Dikembalikan</span>

                        @else
                            <span class="badge bg-secondary">{{ $d->status }}</span>
                        @endif

                    </td>

                    <td>
                        <a href="/anggota/riwayat/detail/{{ $d->id_peminjaman }}"
                           class="btn btn-sm btn-info">
                           <i class="ti ti-eye"></i>
                        </a>
                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>

    </div>

</div>

{{-- MODAL --}}
<div class="modal fade" id="modalAlasan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">

            <h5 class="mb-3 text-danger">Alasan Penolakan</h5>

            <div id="isiAlasan"></div>

            <div class="text-end mt-3">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

@endsection


@push('js')
<script>
function showAlasan(btn) {
    let alasan = btn.getAttribute('data-alasan'); //mengambil dari attribute

    document.getElementById('isiAlasan').innerText = alasan;

    let modal = new bootstrap.Modal(document.getElementById('modalAlasan'));
    modal.show();
}
</script>
@endpush
