<h3 style="text-align:center;">LAPORAN PERPUSTAKAAN</h3>

<p>
    Bulan:
    {{ $bulan ? \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') : 'Semua' }}
    <br>
    Tahun: {{ $tahun ?? 'Semua' }}
</p>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Anggota</th>
        <th>Buku</th>
        <th>Petugas</th>
        <th>Status</th>
        <th>Denda</th>
    </tr>

    @foreach($data as $i => $d)

    @php
        $denda = 0;
        $hari = 0;

        // 🔥 MASIH DIPINJAM & TERLAMBAT
        if ($d->status == 'dipinjam' && now()->gt($d->tgl_kembali)) {

            $hari = \Carbon\Carbon::parse($d->tgl_kembali)
                ->startOfDay()
                ->diffInDays(now()->startOfDay());

            $denda = $hari * 1000;
        }

        // 🔥 SUDAH DIKEMBALIKAN & TERLAMBAT
        elseif ($d->pengembalian && $d->pengembalian->tgl_pengembalian > $d->tgl_kembali) {

            $hari = \Carbon\Carbon::parse($d->tgl_kembali)
                ->startOfDay()
                ->diffInDays(
                    \Carbon\Carbon::parse($d->pengembalian->tgl_pengembalian)->startOfDay()
                );

            $denda = $hari * 1000;
        }

        // 🔥 FORMAT STATUS
        $statusText = '';

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
        <td>{{ $i+1 }}</td>
        <td>{{ $d->anggota->nama ?? '-' }}</td>
        <td>{{ $d->buku->judul ?? '-' }}</td>
        <td>{{ $d->petugas->nama ?? '-' }}</td>

        {{-- STATUS --}}
        <td>
            {{ $statusText }}
        </td>

        {{-- 🔥 DENDA FIX --}}
        <td>
            @if($denda > 0)
                Rp {{ number_format($denda) }}
                <br>
                (Terlambat {{ $hari }} hari)
            @else
                -
            @endif
        </td>
    </tr>

    @endforeach
</table>
