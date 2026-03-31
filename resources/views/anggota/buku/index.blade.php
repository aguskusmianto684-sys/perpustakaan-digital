@extends('layouts.anggota')

@section('content')

<div class="container">

    <h4 class="mb-4">
        <i class="ti ti-book"></i> Daftar Buku
    </h4>

    <div class="row mb-4">
        <div class="col-md-6">
            <input type="text" id="searchBuku" class="form-control"
                   placeholder="Cari judul buku...">
        </div>
    </div>

    <div class="row" id="listBuku">

        @foreach($buku as $b)

        <div class="col-md-4 mb-4 buku-item">

            <div class="card shadow-sm h-100">

                <img src="{{ asset('uploads/buku/'.$b->gambar) }}"
                     class="card-img-top"
                     style="height:200px; object-fit:cover;">

                <div class="card-body text-center d-flex flex-column">

                    <h5 class="card-title fw-bold mb-2">
                        {{ $b->judul }}
                    </h5>

                    <p class="small text-muted mb-3">
                        <b>Penulis :</b> {{ $b->penulis }} <br>
                        <b>Penerbit :</b> {{ $b->penerbit }} <br>
                        <b>Tahun :</b> {{ $b->tahun_terbit }} <br>
                        <b>Kategori :</b> {{ $b->kategori }}
                    </p>

                    @if($b->stok > 0)
                        <span class="badge bg-success mb-3">
                            Stok tersedia
                        </span>
                    @else
                        <span class="badge bg-danger mb-3">
                            Stok habis
                        </span>
                    @endif

                    <div class="mt-auto">

                        <a href="/anggota/buku/detail/{{ $b->id_buku }}"
                           class="btn btn-primary btn-sm">
                            <i class="ti ti-eye"></i> Detail
                        </a>

                        @if($b->stok > 0)
                            <a href="/anggota/pinjam/{{ $b->id_buku }}"
                               class="btn btn-danger btn-sm">
                                Pinjam Buku
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>
                                Stok Habis
                            </button>
                        @endif

                    </div>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>


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

@endsection
