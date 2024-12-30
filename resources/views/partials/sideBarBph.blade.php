<aside id="sidebar">
    <div class="d-flex">
        <button class="toggle-btn" type="button">
            <i class="lni lni-grid-alt"></i>
        </button>
        <div class="sidebar-logo">
            <a href="#">CodzSword</a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="{{ route('dashboard-ukm.index') }}" class="sidebar-link">
                <i class="lni lni-user"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('manage-anggota.index') }}" class="sidebar-link">
                <i class="lni lni-agenda"></i>
                <span>Manage Anggota</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('manage-kegiatan-ukm.index') }}" class="sidebar-link">
                <i class="lni lni-protection"></i>
                <span>Manage Kegiatan</span>
            </a>
            
        </li>
        <li class="sidebar-item">
            <a href="{{ route('manage-kas-ukm.index') }}" class="sidebar-link">
                <i class="lni lni-layout"></i>
                <span>Manage Kas</span>
            </a>
            
        </li>
       
    </ul>
    <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <a href="#" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="lni lni-exit"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>