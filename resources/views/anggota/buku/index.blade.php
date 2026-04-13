@extends('layouts.app')

@section('sidebar')
    @include('layouts.partials.sidebar-anggota')
@endsection

@section('content')
    <div class="container">

        <h4 class="mb-4 fw-semibold">
            <i class="ti ti-book"></i> Daftar Buku
        </h4>

        {{-- SEARCH --}}
        <div class="row mb-3">
            <div class="col-md-12">
                <input type="text" id="searchBuku" class="form-control shadow-sm"
                    placeholder="Cari buku, penulis, atau kategori">
            </div>
        </div>

        {{-- DROPDOWN KATEGORI (SEARCHABLE) --}}
        <div class="mb-3">
            <select id="filterKategori" class="form-control select2">
                <option value="all">Semua Kategori</option>

                @foreach ($buku->pluck('kategori')->unique() as $k)
                    <option value="{{ strtolower($k) }}">
                        {{ $k }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- CHIP KATEGORI (MAKS 6) --}}
        <div class="mb-4 d-flex flex-wrap gap-2">

            <button class="kategori-chip active" data-kategori="all">
                Semua
            </button>

            @php
                $kategoriUnik = $buku->pluck('kategori')->unique()->take(6);
            @endphp

            @foreach ($kategoriUnik as $k)
                <button class="kategori-chip" data-kategori="{{ strtolower($k) }}">
                    {{ $k }}
                </button>
            @endforeach

        </div>

        {{-- LIST BUKU --}}
        <div class="row" id="listBuku">

            @foreach ($buku as $b)
                <div class="col-md-4 mb-4 buku-item" data-kategori="{{ strtolower($b->kategori) }}">

                    <div class="card shadow-sm h-100 border-0 buku-card">

                        <div class="img-wrapper position-relative">
                            <img src="{{ asset('uploads/buku/' . $b->gambar) }}" class="buku-img">

                            @if ($b->stok > 0)
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                    Tersedia
                                </span>
                            @else
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    Habis
                                </span>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">

                            <h6 class="fw-bold mb-2 text-dark text-truncate">
                                {{ $b->judul }}
                            </h6>

                            <p class="small text-muted mb-2">
                                {{ $b->penulis }} • {{ $b->tahun_terbit }}
                            </p>

                            <span class="badge mb-2
                                @if($b->kategori == 'Novel') bg-primary
                                @elseif($b->kategori == 'Teknologi') bg-success
                                @elseif($b->kategori == 'Sejarah') bg-warning
                                @elseif($b->kategori == 'Komik') bg-danger
                                @elseif($b->kategori == 'Pendidikan') bg-info
                                @else bg-secondary
                                @endif
                            ">
                                {{ $b->kategori }}
                            </span>

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

                            <div class="mt-auto d-flex gap-2">

                                <a href="/anggota/buku/detail/{{ $b->id_buku }}"
                                    class="btn btn-outline-primary btn-sm w-100">
                                    Detail
                                </a>

                                @if ($b->stok > 0)
                                    <a href="/anggota/pinjam/{{ $b->id_buku }}"
                                        class="btn btn-danger btn-sm w-100">
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

        <div id="emptyState" class="text-center mt-4" style="display:none;">
            <p class="text-muted">Buku tidak ditemukan</p>
        </div>

    </div>
@endsection


@push('css')
<style>
    .buku-card {
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .buku-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

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

    .kategori-chip {
        border: 1px solid #0d6efd;
        background: white;
        color: #0d6efd;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        transition: 0.3s;
        cursor: pointer;
    }

    .kategori-chip:hover {
        background: #0d6efd;
        color: white;
    }

    .kategori-chip.active {
        background: #0d6efd;
        color: white;
    }
</style>
@endpush


@push('js')
<script>
    $(document).ready(function () {
        $('#filterKategori').select2({
            placeholder: "Pilih kategori",
            allowClear: true
        });
    });

    let items = document.querySelectorAll(".buku-item");
    let buttons = document.querySelectorAll(".kategori-chip");

    document.getElementById("searchBuku").addEventListener("keyup", function() {
        filterData(this.value.toLowerCase(), getKategori());
    });

    buttons.forEach(btn => {
        btn.addEventListener("click", function() {

            buttons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            filterData(
                document.getElementById("searchBuku").value.toLowerCase(),
                this.dataset.kategori
            );
        });
    });

    document.getElementById("filterKategori").addEventListener("change", function () {
        filterData(
            document.getElementById("searchBuku").value.toLowerCase(),
            this.value
        );
    });

    function getKategori() {
        let active = document.querySelector(".kategori-chip.active");
        return active ? active.dataset.kategori : "all";
    }

    function filterData(keyword, kategori) {
        let visible = 0;

        items.forEach(item => {
            let text = item.innerText.toLowerCase();
            let matchSearch = text.includes(keyword);
            let matchKategori = kategori === "all" || item.dataset.kategori === kategori;

            if (matchSearch && matchKategori) {
                item.style.display = "";
                visible++;
            } else {
                item.style.display = "none";
            }
        });

        document.getElementById("emptyState").style.display =
            visible === 0 ? "block" : "none";
    }
</script>
@endpush
