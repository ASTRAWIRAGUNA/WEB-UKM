<nav class="navbar navbar-expand-lg bg-primary">
  <div class="container d-flex justify-content-between align-items-center">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="{{ asset('assets/images/BEM.png') }}" alt="Logo BEM" style="width: 30px; height: 30px;" class="me-2">
      <span class="text-light fw-bold">BEM STIMATA</span>
    </a>

    <!-- User Info & Logout -->
    <div class="d-flex align-items-center">
      <span class="text-light me-3 mb-1">Hi,  {{ auth()->user()->email }}</span>
      <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
      <a href="#" class="text-light" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fa-solid fa-arrow-right-from-bracket fs-6"></i>
      </a>
    </div>
  </div>
</nav>