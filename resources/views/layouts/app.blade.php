<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Digital</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/logos/buku.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">


    @stack('css')
</head>

<body>

    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        {{-- SIDEBAR --}}
        @yield('sidebar')

        <div class="body-wrapper">

            {{-- HEADER --}}
            @include('layouts.partials.header')

            {{-- CONTENT --}}
            <div class="container-fluid">
                @yield('content')
            </div>

            {{-- FOOTER --}}
            @include('layouts.partials.footer')

        </div>
    </div>

    {{-- SCRIPT GLOBAL --}}
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    {{-- DATATABLE --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{-- BUTTON EXPORT --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    {{-- SWEETALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    {{-- ALERT ERROR --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}'
            });
        </script>
    @endif

    {{-- DATATABLE BASIC --}}
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "Cari Data:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });
        });
    </script>

    {{-- DATATABLE LAPORAN EXPORT --}}
    <script>
        $(document).ready(function() {

            if ($('#laporanTable').length) {

                $('#laporanTable').DataTable({

                    dom: 'Bfrtip',

                    buttons: [{
                            extend: 'copy',
                            text: 'Copy'
                        },
                        {
                            extend: 'excel',
                            text: 'Excel'
                        },

                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            text: 'PDF',
                            title: 'Laporan Peminjaman Buku Perpustakaan Digital',
                            orientation: 'portrait',
                            pageSize: 'A4',

                            customize: function(doc) {

                                doc.styles.title = {
                                    alignment: 'center',
                                    fontSize: 16,
                                    bold: true
                                };

                                doc.styles.tableHeader = {
                                    alignment: 'center',
                                    bold: true,
                                    fontSize: 11,
                                    fillColor: '#2d4154',
                                    color: 'white'
                                };

                                doc.defaultStyle.fontSize = 10;
                                doc.defaultStyle.alignment = 'center';

                                doc.content[1].table.widths = ['5%', '20%', '20%', '20%', '15%',
                                    '10%', '20%'
                                ];
                            }
                        },

                        {
                            extend: 'print',
                            text: 'Print'
                        }
                    ]

                });

            }

        });
    </script>

    <script>
        $(document).ready(function() {

            if ($.fn.DataTable.isDataTable('#laporanTable')) {
                $('#laporanTable').DataTable().destroy();
            }

            $('#laporanTable').DataTable({
                dom: 'Bfrtip',

                buttons: [{
                    extend: 'print',
                    text: 'Print',
                    title: 'Laporan Riwayat Peminjaman Buku',

                    customize: function(win) {
                        $(win.document.body).find('h1')
                            .css('text-align', 'center')
                            .css('font-size', '16px');

                        $(win.document.body).find('table')
                            .addClass('table-bordered')
                            .css('font-size', '12px');
                    }
                }]
            });

        });
    </script>

    {{-- FIX BACK BUTTON --}}
    <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

    @stack('js')

</body>

</html>
