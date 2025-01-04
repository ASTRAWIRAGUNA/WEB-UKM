<nav class="navbar bg-body-tertiary bg-primary">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="{{ asset('assets/images/BEM.png')}}" alt="" srcset="" style="width: 30px; height: 30px;">
      </a>
    </div>
    <div>
      <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
          @csrf
      </form>
      
      <a href="#" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="lni lni-exit"></i>
          <span>Logout</span>
      </a>
    </div>
</nav>