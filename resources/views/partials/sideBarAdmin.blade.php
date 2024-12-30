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
            <a href="{{ route('dashboard-admin.index') }}" class="sidebar-link">
                <i class="lni lni-user"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('manage-user.index') }}" class="sidebar-link">
                <i class="lni lni-agenda"></i>
                <span>Manage User</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('manage-ukm.index') }}" class="sidebar-link">
                <i class="lni lni-protection"></i>
                <span>Manage UKM</span>
            </a>
           
        </li>
        <li class="sidebar-item">
            <a href="{{ route('manage-laporan-ukm.index') }}" class="sidebar-link">
                <i class="lni lni-protection"></i>
                <span>Laporan UKM</span>
            </a>
           
        </li>
        <li class="sidebar-item">
            <a href="{{ route('manage-ukm.index') }}" class="sidebar-link">
                <i class="lni lni-protection"></i>
                <span>Laporan UKM</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('log-activity.index') }}" class="sidebar-link">
                <i class="lni lni-layout"></i>
                <span>Log Activity</span>
            </a>
        </li>
        
        
    </ul>
    <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        
        <a href="{{ route('auth.logout') }}" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="lni lni-exit"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>