@extends('base')

@section('head')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
@endsection

@section('body')
<div class="wrapper">
    @include('partials.navbarMahasiswa')

    <div class="main p-3">

        <div class="container mt-4">
            <div class="row justify-content-center gap-3">
                @foreach ($activities as $activity)
                    <div class="col-auto bg-light rounded-md" style="width: 25rem;">
                        <div class="d-flex align-items-center justify-content-between p-3 text-center">
                            <h5 class="">{{ $activity->name_activity }}</h5>
                            <button class="btn btn-success ml-4" data-bs-toggle="modal" data-bs-target="#qrScanModal">
                                <i class="fa-solid fa-expand text-white"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="qrScanModal" tabindex="-1" aria-labelledby="qrScanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrScanModalLabel">Scan QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tempat untuk scanner -->
                <div id="qrReader" style="width: 100%; height: 300px;"></div>
                <p class="text-center mt-3">Arahkan kamera ke QR Code untuk absen.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let html5QrCode;
        let scannerStarted = false;

        const modal = document.getElementById('qrScanModal');

        // Fungsi untuk memulai scanner
        const startScanner = () => {
            if (scannerStarted) return; // Jika scanner sudah berjalan, tidak perlu memulai lagi

            html5QrCode = new Html5Qrcode("qrReader");
            html5QrCode.start(
                { facingMode: "environment" }, // Gunakan kamera belakang
                { fps: 10, qrbox: { width: 250, height: 250 } }, // Konfigurasi scanner
                (decodedText) => {
                    console.log(`Kode QR ditemukan: ${decodedText}`);
                    alert(`Berhasil: ${decodedText}`);
                    stopScanner(); // Hentikan scanner setelah berhasil membaca QR
                    bootstrap.Modal.getInstance(modal).hide(); // Tutup modal
                },
                (errorMessage) => {
                    console.warn(`Kesalahan QR Code: ${errorMessage}`);
                }
            ).then(() => {
                scannerStarted = true;
                console.log("Scanner dimulai.");
            }).catch(err => {
                console.error(`Kesalahan memulai scanner: ${err}`);
            });
        };

        // Fungsi untuk menghentikan scanner
        const stopScanner = () => {
            if (!scannerStarted) return; // Jika scanner belum berjalan, abaikan

            html5QrCode.stop().then(() => {
                console.log("Scanner dihentikan.");
            }).catch(err => {
                console.error(`Kesalahan menghentikan scanner: ${err}`);
            });

            scannerStarted = false;
        };

        // Event listener untuk membuka modal
        modal.addEventListener('shown.bs.modal', () => {
            console.log("Modal dibuka.");
            startScanner(); // Mulai scanner saat modal dibuka
        });

        // Event listener untuk menutup modal
        modal.addEventListener('hidden.bs.modal', () => {
            console.log("Modal ditutup.");
            stopScanner(); // Hentikan scanner saat modal ditutup
        });
    });
</script>
@endsection