<nav class="navbar bg-body-tertiary bg-primary">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="Bootstrap" width="30" height="24">
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