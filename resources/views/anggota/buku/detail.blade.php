@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')

<div class="container">

    {{-- judul halaman --}}
    <h4 class="mb-4 fw-semibold">
        <i class="ti ti-book"></i> Detail Buku
    </h4>

    <div class="card shadow-sm border-0">
        <div class="row g-0">

            {{-- gambar buku --}}
            <div class="col-md-4">
                <img src="{{ asset('uploads/buku/'.$buku->gambar) }}"
                     class="img-fluid rounded-start w-100"
                     style="height: 100%; object-fit: cover;">
            </div>

            <div class="col-md-8">
                <div class="card-body d-flex flex-column">

                    {{-- judul buku --}}
                    <h4 class="fw-bold mb-2">
                        {{ $buku->judul }}
                    </h4>

                    {{-- info buku --}}
                    <p class="text-muted mb-3 small">
                        <b>Penulis:</b> {{ $buku->penulis }} <br>
                        <b>Penerbit:</b> {{ $buku->penerbit }} <br>
                        <b>Tahun:</b> {{ $buku->tahun_terbit }} <br>
                        <b>Kategori:</b> {{ \Illuminate\Support\Str::limit($buku->kategori, 30) }}
                    </p>

                    {{-- deskripsi buku --}}
                    <p class="mb-3">
                        {{ $buku->deskripsi }}
                    </p>

                    {{-- status stok --}}
                    @if($buku->stok > 0)
                        <span class="mb-3">
                            Stok: {{ $buku->stok }}
                        </span>
                    @else
                        <span class="badge bg-danger mb-3">
                            Habis
                        </span>
                    @endif

                    {{-- tombol aksi --}}
                    <div class="mt-3 d-flex gap-2">

                        {{-- tombol kembali --}}
                        <a href="/anggota/buku" class="btn btn-secondary btn-sm">
                            Kembali
                        </a>

                        {{-- tombol pinjam --}}
                        @if($buku->stok > 0)
                            <a href="/anggota/pinjam/{{ $buku->id_buku }}"
                               class="btn btn-danger btn-sm">
                                Pinjam
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>
                                Habis
                            </button>
                        @endif

                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection
