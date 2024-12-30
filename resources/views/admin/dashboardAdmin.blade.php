@extends('base')

@section('head')
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/styleAdmin.css') }}">
@endsection

@section('body')
<div class="wrapper">
    @include('partials.sideBarAdmin')

    <div class="main p-3">
        <div class="text-center">
            <h1>Dashboard Admin</h1>
        </div>

        <div class="mb-3">
            <!-- Form untuk toggle status semua UKM -->
            <form action="{{ route('dashboard-admin.toggleAllRegistrationStatus') }}" method="POST" id="toggleForm">
                @csrf
                <div class="form-check form-switch">
                    <!-- Menambahkan kondisi untuk status checkbox -->
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="status" value="on"
                    @if($isActive) checked @endif>
                    <label class="form-check-label" for="flexSwitchCheckDefault">Aktifkan Semua Pendaftaran UKM</label>
                </div>
            </form>
                 
        </div>
    </div>
</div>

  <script src="{{ asset('assets/js/hamburger.js')}} "></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function() {
    $('#flexSwitchCheckDefault').on('change', function() {
        var status = $(this).is(':checked') ? 'on' : 'off';  // Determine the status based on the switch position
        console.log("Switch status changed to: " + status);  // Log the status change

        // Send an AJAX request to update the status
        $.ajax({
            url: '{{ route("dashboard-admin.toggleAllRegistrationStatus") }}',  // URL to send the request to
            type: 'POST',  // HTTP method to use for the request
            data: {
                _token: '{{ csrf_token() }}',  // CSRF token for security
                status: status  // Status to be sent to the server
            },
            success: function(response) {
                console.log("Server response: ", response);  // Log the server response
                alert(response.message);  // Show a success message

                // Update the switch status in the UI based on the server response
                if (response.status === 'on') {
                    $('#flexSwitchCheckDefault').prop('checked', true);
                } else if (response.status === 'off') {
                    $('#flexSwitchCheckDefault').prop('checked', false);
                } else {
                    console.log("Unexpected response status: " + response.status);
                }
            },
            error: function(xhr, status, error) {
                console.log("Terjadi kesalahan: " + error);  // Log the error
                alert("Gagal mengubah status. Silakan coba lagi.");  // Show an error message
                // Revert the switch status if an error occurs
                $('#flexSwitchCheckDefault').prop('checked', !$('#flexSwitchCheckDefault').is(':checked'));
            }
        });
    });
});
</script>
@endsection
