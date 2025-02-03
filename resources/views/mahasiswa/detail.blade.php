@extends('base')

@section('head')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    svg {
        width: 100px;
        height: 100px;
    }
</style>
@endsection

@section('body')
<div class="wrapper">
    @include('partials.navbarMahasiswa')

    <div class="main p-3">
        <div class="container mt-4">
            <div class="row justify-content-center gap-3">
                @foreach ($activities as $activity)
                    <div class="col-auto bg-light rounded-md p-3" style="width: 25rem;">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5>{{ $activity->name_activity }}</h5>
                            <button class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#qrScanModal-{{ $activity->activities_id }}">
                                <i class="fa-solid fa-qrcode text-white"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@foreach ($activities as $activity)
    <div class="modal fade" id="qrScanModal-{{ $activity->activities_id }}"
        data-activity-id="{{ $activity->activities_id }}" tabindex="-1"
        aria-labelledby="qrScanModalLabel-{{ $activity->activities_id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="qrScanner-{{ $activity->activities_id }}" style="width: 100%; height: 350px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach


<script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let scanners = {};
    let isScanning = false; // Flag untuk mencegah request ganda

    $(document).on('hidden.bs.modal', function () {
        // Mengecek jika tidak ada modal yang aktif
        if ($('.modal:visible').length === 0) {
            // Hapus backdrop hanya jika tidak ada modal yang aktif
            $('.modal-backdrop').remove();
        }
    });

    $(document).on('shown.bs.modal', '[id^="qrScanModal-"]', function () {
        let modal = $(this);
        let activityId = modal.data('activity-id'); // Ambil ID aktivitas dari data modal
        let scannerId = `qrScanner-${activityId}`;

        // Cek apakah sudah ada scanner aktif untuk modal ini
        if (scanners[scannerId]) {
            // Jika scanner sudah ada, jangan mulai lagi
            return;
        }

        // Pastikan div untuk scanner sudah ada di DOM
        let qrScannerElement = document.getElementById(scannerId);
        if (qrScannerElement) {
            // Jika belum ada scanner, buat dan mulai scanner baru
            scanners[scannerId] = new Html5Qrcode(scannerId);

            scanners[scannerId].start(
                { facingMode: "environment" }, // Menggunakan kamera belakang
                { fps: 10, qrbox: { width: 250, height: 250 } }, // FPS dan ukuran QR code
                function onScanSuccess(decodedText) {
                    if (isScanning) return; // Mencegah request ganda
                    isScanning = true;

                    console.log("Scanned QR:", decodedText);

                    let parts = decodedText.split('-');
                    if (parts.length !== 4 || parts[0] !== "UKM" || parts[2] !== "ACT") {
                        Swal.fire({
                            icon: 'error',
                            title: 'QR Code Tidak Valid!',
                            text: 'QR Code ini tidak sesuai format.',
                            timer: 3000,
                            showConfirmButton: false
                        });
                        isScanning = false; // Reset flag
                        return;
                    }

                    let parsedUkmId = parseInt(parts[1], 10); // Pastikan ukm_id valid
                    let parsedActivityId = parts[3]; // Ambil ID aktivitas dari QR Code

                    // Kirim data ke server untuk validasi absensi
                    $.ajax({
                        url: '{{ route("attendance.scan") }}',
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: "application/json",
                        data: JSON.stringify({
                            activities_id: activityId,
                            ukm_id: parsedUkmId,
                            qr_code: decodedText
                        }),
                        success: function (response) {
                            console.log("Server response:", response);
                            modal.modal('hide'); // Sembunyikan modal setelah berhasil

                            if (response.invalid_qr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'QR Code Tidak Valid!',
                                    text: 'QR Code ini bukan untuk aktivitas ini.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else if (response.already_absent) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Anda sudah absen!',
                                    text: 'Anda telah melakukan absensi sebelumnya.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Absen Berhasil!',
                                    text: 'Absensi Anda telah tercatat.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function (xhr) {
                            console.error("Error response:", xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: 'Gagal mengirim data ke server.',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        },
                        complete: function () {
                            isScanning = false; // Reset flag setelah request selesai
                        }
                    });
                }
            ).catch(err => {
                console.error("Error memulai kamera:", err); // Menangani error jika ada
            });
        }
    });

    $(document).on('hidden.bs.modal', '[id^="qrScanModal-"]', function () {
        let modal = $(this);
        let activityId = modal.data('activity-id');
        let scannerId = `qrScanner-${activityId}`;

        // Hentikan dan hapus scanner saat modal ditutup
        if (scanners[scannerId]) {
            scanners[scannerId].stop().then(() => {
                scanners[scannerId].clear();
                delete scanners[scannerId];
            }).catch(err => console.error("Error menghentikan scanner:", err));
        }
    });


</script>

@endsection