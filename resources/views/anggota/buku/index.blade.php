@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')
    <div class="container">

        <h4 class="mb-4 fw-semibold">
            <i class="ti ti-book"></i> Daftar Buku
        </h4>

        {{-- 🔥 SEARCH --}}
        <div class="row mb-3">
            <div class="col-md-12">
                <input type="text" id="searchBuku" class="form-control shadow-sm" placeholder="🔍 Cari judul buku...">
            </div>
        </div>

        {{-- 🔥 FILTER KATEGORI --}}
        <div class="mb-4 d-flex flex-wrap gap-2">

            <button class="btn btn-sm btn-primary filter-btn" data-kategori="all">
                Semua
            </button>

            @php
                $kategoriUnik = $buku->pluck('kategori')->unique();
            @endphp

            @foreach ($kategoriUnik as $k)
                <button class="btn btn-sm btn-outline-primary filter-btn" data-kategori="{{ strtolower($k) }}">
                    {{ $k }}
                </button>
            @endforeach

        </div>

        {{-- 🔥 LIST BUKU --}}
        <div class="row" id="listBuku">

            @foreach ($buku as $b)
                <div class="col-md-4 mb-4 buku-item" data-kategori="{{ strtolower($b->kategori) }}">

                    <div class="card shadow-sm h-100 border-0 buku-card">

                        {{-- GAMBAR --}}
                        <div class="img-wrapper">
                            <img src="{{ asset('uploads/buku/' . $b->gambar) }}" class="buku-img">
                        </div>

                        <div class="card-body d-flex flex-column">

                            {{-- JUDUL --}}
                            <h6 class="fw-bold mb-2 text-dark">
                                {{ $b->judul }}
                            </h6>

                            {{-- INFO --}}
                            <p class="small text-muted mb-2">
                                {{ $b->penulis }} • {{ $b->tahun_terbit }}
                            </p>

                            {{-- KATEGORI --}}
                            <span class="badge bg-light text-dark mb-2">
                                {{ $b->kategori }}
                            </span>

                            {{-- STOK --}}
                            <p class="mb-2">
                                @if ($b->stok > 0)
                                    <span class="text-success fw-semibold">
                                        {{ $b->stok }} tersedia
                                    </span>
                                @else
                                    <span class="text-danger fw-semibold">
                                        Habis
                                    </span>
                                @endif
                            </p>

                            {{-- BUTTON --}}
                            <div class="mt-auto d-flex gap-2">

                                <a href="/anggota/buku/detail/{{ $b->id_buku }}"
                                    class="btn btn-outline-primary btn-sm w-100">
                                    Detail
                                </a>

                                @if ($b->stok > 0)
                                    <a href="/anggota/pinjam/{{ $b->id_buku }}" class="btn btn-danger btn-sm w-100">
                                        Pinjam
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-sm w-100" disabled>
                                        Habis
                                    </button>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>
@endsection


{{-- 🔥 STYLE --}}
@push('css')
    <style>
        /* CARD */
        .buku-card {
            border-radius: 15px;
            transition: 0.3s;
        }

        .buku-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* GAMBAR */
        .img-wrapper {
            height: 180px;
            overflow: hidden;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .buku-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* FILTER BUTTON */
        .filter-btn.active {
            background: #0d6efd;
            color: white;
        }
    </style>
@endpush


{{-- 🔥 SCRIPT --}}
@push('js')
    <script>
        // 🔍 SEARCH
        document.getElementById("searchBuku").addEventListener("keyup", function() {
            let keyword = this.value.toLowerCase();
            let buku = document.querySelectorAll(".buku-item");

            buku.forEach(function(item) {
                let text = item.innerText.toLowerCase();
                item.style.display = text.includes(keyword) ? "" : "none";
            });
        });


        // 🔥 FILTER KATEGORI
        let buttons = document.querySelectorAll(".filter-btn");
        let items = document.querySelectorAll(".buku-item");

        buttons.forEach(btn => {
            btn.addEventListener("click", function() {

                let kategori = this.dataset.kategori;

                // aktif button
                buttons.forEach(b => b.classList.remove("active"));
                this.classList.add("active");

                items.forEach(item => {

                    if (kategori === "all") {
                        item.style.display = "";
                    } else {
                        item.style.display =
                            item.dataset.kategori === kategori ? "" : "none";
                    }

                });

            });
        });
    </script>
@endpush
