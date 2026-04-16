<h3 style="text-align:center; margin-bottom:5px;">
    LAPORAN PERPUSTAKAAN
</h3>

<p style="text-align:center; margin-top:0;">
    Periode:
    {{ $bulan ? \Carbon\Carbon::create()->month((int) $bulan)->translatedFormat('F') : 'Semua Bulan' }}
    {{ $tahun ?? '' }}
</p>

<p style="text-align:center; font-size:12px;">
    Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
</p>

<br>

<table border="1" width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse; font-size:12px;">
    <thead>
        <tr style="background:#f2f2f2;">
            <th>No</th>
            <th>Anggota</th>
            <th>Buku</th>
            <th>Petugas</th>
            <th>Status</th>
            <th>Denda</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $i => $d)
            @php
                $denda = 0;
                $hari = 0;

                //  MASIH DIPINJAM & TERLAMBAT
                if ($d->status == 'dipinjam' && now()->gt($d->tgl_kembali)) {
                    $hari = \Carbon\Carbon::parse($d->tgl_kembali)
                        ->startOfDay()
                        ->diffInDays(now()->startOfDay());

                    $denda = $hari * 1000;
                }

                //  SUDAH DIKEMBALIKAN & TERLAMBAT
                elseif ($d->pengembalian && $d->pengembalian->tgl_pengembalian > $d->tgl_kembali) {
                    $hari = \Carbon\Carbon::parse($d->tgl_kembali)
                        ->startOfDay()
                        ->diffInDays(\Carbon\Carbon::parse($d->pengembalian->tgl_pengembalian)->startOfDay());

                    $denda = $hari * 1000;
                }

                // FORMAT STATUS
                if ($d->status == 'menunggu') {
                    $statusText = 'Menunggu';
                } elseif ($d->status == 'dipinjam') {
                    $statusText = 'Dipinjam';
                } elseif ($d->status == 'ditolak') {
                    $statusText = 'Ditolak';
                } else {
                    $statusText = 'Dikembalikan';
                }
            @endphp

            <tr>
                <td align="center">{{ $i + 1 }}</td>
                <td>{{ $d->anggota->nama ?? '-' }}</td>
                <td>{{ $d->buku->judul ?? '-' }}</td>
                <td>{{ $d->petugas->nama ?? '-' }}</td>

                {{-- STATUS --}}
                <td align="center">
                    {{ $statusText }}
                </td>

                {{-- DENDA --}}
                <td align="center">
                    @if ($denda > 0)
                        <b>Rp {{ number_format($denda) }}</b>
                        <br>
                        <small>(Terlambat {{ $hari }} hari)</small>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<br><br>

{{-- TANGGAL --}}
<p style="text-align:right;">
    Banjar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
</p>

<br><br><br>

{{-- TANDA TANGAN --}}
<table width="100%" style="text-align:center;">
    <tr>
        <td>
            Mengetahui,<br>
            Kepala Perpustakaan
            <br><br><br><br>
            <u><b>_____________________</b></u>
        </td>

        <td>
            Dibuat oleh,<br>
            Petugas Perpustakaan
            <br><br><br><br>
            <u><b>_____________________</b></u>
        </td>
    </tr>
</table>
