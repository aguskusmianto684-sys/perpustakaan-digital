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
    <div class="row mb-4">
        <div class="col-md-17">
            <input type="text" id="searchBuku" class="form-control shadow-sm"
                   placeholder="🔍 Cari judul buku...">
        </div>
    </div>

    <div class="row" id="listBuku">

        @foreach($buku as $b)

        <div class="col-md-4 mb-4 buku-item">

            <div class="card shadow-sm h-100 border-0">

                {{-- GAMBAR --}}
                <img src="{{ asset('uploads/buku/'.$b->gambar) }}"
                     class="card-img-top"
                     style="height:200px; object-fit:cover;">

                <div class="card-body d-flex flex-column">

                    {{-- JUDUL --}}
                    <h6 class="fw-bold mb-2 text-dark">
                        {{ $b->judul }}
                    </h6>

                    {{-- INFO --}}
                    <p class="small text-muted mb-2">
                        <b>Penulis:</b> {{ $b->penulis }} <br>
                        <b>Tahun:</b> {{ $b->tahun_terbit }}
                    </p>

                    {{-- KATEGORI --}}
                    <p class="small text-muted mb-2">
                        <b>Kategori:</b>
                        {{ \Illuminate\Support\Str::limit($b->kategori, 15) }}
                    </p>

                    {{-- STOK --}}
                    <p class="mb-2">
                        <b>Stok:</b>
                        @if($b->stok > 0)
                            <span class="text-success fw-semibold">
                                {{ $b->stok }} tersedia
                            </span>
                        @else
                            <span class="text-danger fw-semibold">
                                Habis
                            </span>
                        @endif
                    </p>

                    {{-- STATUS --}}
                    @if($b->stok > 0)
                        <span class="badge bg-success mb-3">
                            Tersedia
                        </span>
                    @else
                        <span class="badge bg-danger mb-3">
                            Dipinjam
                        </span>
                    @endif

                    {{-- BUTTON --}}
                    <div class="mt-auto d-flex gap-2">

                        <a href="/anggota/buku/detail/{{ $b->id_buku }}"
                           class="btn btn-outline-primary btn-sm w-100">
                            Detail
                        </a>

                        @if($b->stok > 0)
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

</div>

@endsection


@push('js')
<script>
document.getElementById("searchBuku").addEventListener("keyup", function () {

    let keyword = this.value.toLowerCase();
    let buku = document.querySelectorAll(".buku-item");

    buku.forEach(function (item) {

        let text = item.innerText.toLowerCase();

        if (text.includes(keyword)) {
            item.style.display = "";
        } else {
            item.style.display = "none";
        }

    });

});
</script>
@endpush
