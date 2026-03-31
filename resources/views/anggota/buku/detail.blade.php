@extends('layouts.anggota')

@section('content')

<div class="container">

    <h4 class="mb-4">Detail Buku</h4>

    <div class="card shadow-sm">
        <div class="row g-0">

            {{-- GAMBAR --}}
            <div class="col-md-4">
                <img src="{{ asset('uploads/buku/'.$buku->gambar) }}"
                     class="img-fluid rounded-start w-100"
                     style="height: 100%; object-fit: cover;">
            </div>

            {{-- DETAIL --}}
            <div class="col-md-8">
                <div class="card-body">

                    <h4 class="card-title">{{ $buku->judul }}</h4>

                    <p class="text-muted mb-2">
                        Penulis : {{ $buku->penulis }} <br>
                        Penerbit : {{ $buku->penerbit }} <br>
                        Tahun : {{ $buku->tahun_terbit }} <br>
                        Kategori : {{ $buku->kategori }}
                    </p>

                    <p>{{ $buku->deskripsi }}</p>

                    {{-- STATUS STOK --}}
                    @if($buku->stok > 0)
                        <span class="badge bg-success mb-3">Stok tersedia</span>
                    @else
                        <span class="badge bg-danger mb-3">Stok habis</span>
                    @endif

                    {{-- BUTTON --}}
                    <div class="mt-3">

                        <a href="/anggota/buku" class="btn btn-secondary">
                            Kembali
                        </a>

                        @if($buku->stok > 0)
                            <a href="/anggota/pinjam/{{ $buku->id_buku }}"
                               class="btn btn-danger">
                                Pinjam Buku
                            </a>
                        @endif

                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection
