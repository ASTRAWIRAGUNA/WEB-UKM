<aside id="sidebar">
    <div class="d-flex">
        <button class="toggle-btn shadow-none" type="button">
            <img src="{{ asset('assets/images/BEM.png')}}" alt="" srcset="" style="width: 30px; height: 30px;">
        </button>
        <div class="sidebar-logo">
            <a href="#">CodzSword</a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item {{ request()->routeIs('dashboard-ukm.index') ? 'active' : '' }}">
            <a href="{{ route('dashboard-ukm.index') }}" class="sidebar-link">
                <i class="fa-solid fa-gauge"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('manage-anggota.index') ? 'active' : '' }}">
            <a href="{{ route('manage-anggota.index') }}" class="sidebar-link">
                <i class="fa-solid fa-user-group"></i>
                <span>Manage Anggota</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('manage-kegiatan-ukm.index') ? 'active' : '' }}">
            <a href="{{ route('manage-kegiatan-ukm.index') }}" class="sidebar-link">
                <i class="fa-solid fa-chart-line"></i>
                <span>Manage Kegiatan</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('manage-kas-ukm.index') ? 'active' : '' }}">
            <a href="{{ route('manage-kas-ukm.index') }}" class="sidebar-link">
                <i class="fa-solid fa-money-bill-wave"></i>
                <span>Manage Kas</span>
            </a>
        </li>
    </ul>
    <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <a href="#" class="sidebar-link"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>