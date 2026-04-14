@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-petugas')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <h4 class="mb-3">
                <i class="ti ti-book"></i> Data Peminjaman
            </h4>

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
                        @foreach ($peminjaman as $index => $p)
                            <tr>

                                <td>{{ $index + 1 }}</td>
                                <td>{{ $p->anggota->nama ?? '-' }}</td>
                                <td>{{ $p->buku->judul ?? '-' }}</td>
                                <td>{{ $p->tgl_pinjam }}</td>
                                <td>{{ $p->tgl_kembali }}</td>

                                {{-- DENDA --}}
                                <td>
                                    @php
                                        $dendaAsli = $p->pengembalian->denda ?? 0;
                                    @endphp

                                    {{-- 🔥 JIKA DIPINJAM ATAU MENUNGGU PENGEMBALIAN DAN TELAT --}}
                                    @if (in_array($p->status, ['dipinjam', 'menunggu pengembalian']) && now()->gt($p->tgl_kembali))
                                        @php
                                            $hari = \Carbon\Carbon::parse($p->tgl_kembali)->diffInDays(now(), false);
                                            $hari = max(0, floor($hari));
                                            $dendaTelat = $hari * 1000;
                                        @endphp

                                        <span class="text-danger fw-semibold">
                                            Rp {{ number_format($dendaTelat) }}
                                        </span>
                                        <br>
                                        <small class="text-danger">
                                            Terlambat {{ $hari }} hari
                                        </small>

                                        {{-- 🔥 JIKA SUDAH DIKEMBALIKAN --}}
                                    @elseif ($p->status == 'dikembalikan')
                                        @if ($dendaAsli > 0)
                                            <span class="text-danger fw-semibold">
                                                Rp {{ number_format($dendaAsli) }}
                                            </span><br>

                                            @if ($p->denda == 0)
                                                <small class="text-success">Sudah Lunas</small>
                                            @else
                                                <small class="text-warning">Menunggu Pembayaran</small>
                                            @endif
                                        @else
                                            <small class="text-success">Tepat Waktu</small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if ($p->status == 'dipinjam' && now()->gt($p->tgl_kembali))
                                        <span class="badge bg-danger">Terlambat</span>
                                    @elseif($p->status == 'dipinjam')
                                        <span class="badge bg-primary">Dipinjam</span>
                                    @elseif($p->status == 'menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($p->status == 'menunggu pengembalian')
                                        <span class="badge bg-info">Menunggu Pengembalian</span>
                                    @elseif($p->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span><br>
                                        <small class="text-danger">{{ $p->alasan }}</small>
                                    @elseif($p->status == 'dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">

                                        <a href="/petugas/peminjaman/detail/{{ $p->id_peminjaman }}"
                                            class="btn btn-sm btn-info">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        {{-- STRUK --}}
                                        @if ($p->status == 'dikembalikan' && $p->denda == 0)
                                            <button class="btn btn-sm btn-success"
                                                onclick="showStruk(
                                        '{{ $p->anggota->nama }}',
                                        '{{ $p->buku->judul }}',
                                        '{{ $p->pengembalian->denda ?? 0 }}'
                                    )">
                                                🧾
                                            </button>
                                        @endif

                                        {{-- DROPDOWN --}}
                                        @if (
                                            $p->status == 'menunggu' ||
                                                $p->status == 'menunggu pengembalian' ||
                                                ($p->status == 'dikembalikan' && ($p->pengembalian->denda ?? 0) > 0 && $p->denda != 0) ||
                                                $p->status == 'ditolak')
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-secondary" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">

                                                    @if ($p->status == 'menunggu')
                                                        <li>
                                                            <a class="dropdown-item text-success"
                                                                href="/petugas/peminjaman/konfirmasi/{{ $p->id_peminjaman }}">
                                                                Konfirmasi
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#"
                                                                onclick="tolakPeminjaman({{ $p->id_peminjaman }})">
                                                                Tolak
                                                            </a>
                                                        </li>
                                                    @elseif($p->status == 'menunggu pengembalian')
                                                        <li>
                                                            <a class="dropdown-item text-success"
                                                                href="/petugas/peminjaman/kembalikan/{{ $p->id_peminjaman }}">
                                                                Konfirmasi Pengembalian
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#"
                                                                onclick="tolakPengembalian({{ $p->id_peminjaman }})">
                                                                Tolak
                                                            </a>
                                                        </li>
                                                    @elseif($p->status == 'dikembalikan' && ($p->pengembalian->denda ?? 0) > 0 && $p->denda != 0)
                                                        <li>
                                                            <a class="dropdown-item text-primary"
                                                                href="/petugas/peminjaman/bayar/{{ $p->id_peminjaman }}">
                                                                Konfirmasi Pembayaran
                                                            </a>
                                                        </li>
                                                    @elseif($p->status == 'ditolak')
                                                        <li>
                                                            <span class="dropdown-item text-muted text-center">
                                                                Tidak ada aksi
                                                            </span>
                                                        </li>
                                                    @endif

                                                </ul>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    {{-- MODAL STRUK --}}
    <div class="modal fade" id="modalStruk" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">

                <div id="areaStruk" style="font-family: monospace; font-size:12px; width:260px; margin:auto;">
                    <div style="text-align:center;">
                        <strong>PERPUSTAKAAN DIGITAL</strong><br>
                        <small>Digital Library</small>
                    </div>

                    <hr style="border-top:1px dashed black;">
                    <div id="strukContent"></div>
                    <hr style="border-top:1px dashed black;">

                    <div style="text-align:center;">
                        TERIMA KASIH
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button onclick="printStruk()" class="btn btn-success btn-sm">
                        Print
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        function tolakPeminjaman(id) {
            let alasan = prompt("1. Buku dipinjam\n2. Stok habis\n3. Melebihi batas\n4. Data tidak valid");
            if (alasan) {
                window.location.href = "/petugas/peminjaman/tolak/" + id + "/" + alasan;
            }
        }

        function tolakPengembalian(id) {
            let alasan = prompt("1. Buku belum kembali\n2. Buku rusak\n3. Data salah\n4. Perlu cek");
            if (alasan) {
                window.location.href = "/petugas/peminjaman/tolak-pengembalian/" + id + "/" + alasan;
            }
        }

        function showStruk(nama, buku, denda) {
            let html = `
        <div>ID : TRX-${Math.floor(Math.random()*100000)}</div>
        <div>Nama : ${nama}</div>
        <div>Buku : ${buku}</div>
        <div>Denda : Rp ${denda}</div>
        <hr>
        <b>TOTAL : Rp ${denda}</b>
    `;
            document.getElementById('strukContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('modalStruk')).show();
        }

        function printStruk() {
            let isi = document.getElementById('areaStruk').innerHTML;
            let win = window.open('', '', 'width=300,height=600');
            win.document.write(`<body onload="window.print();window.close()">${isi}</body>`);
            win.document.close();
        }
    </script>
@endpush
