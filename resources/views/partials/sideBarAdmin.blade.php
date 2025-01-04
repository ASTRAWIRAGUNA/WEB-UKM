<aside id="sidebar">
    <div class="d-flex">
        <button class="toggle-btn" type="button">
            <img src="{{ asset('assets/images/BEM.png')}}" alt="" srcset="" style="width: 30px; height: 30px;">
        </button>
        <div class="sidebar-logo">
            <a href="#">BEM STIMATA</a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="{{ route('dashboard-admin.index') }}" class="sidebar-link">
                <i class="fa-solid fa-gauge"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('manage-user.index') }}" class="sidebar-link">
                <i class="fa-regular fa-user"></i>
                <span>Manage User</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('manage-ukm.index') }}" class="sidebar-link">
                <i class="fa-solid fa-users-rectangle"></i>
                <span>Manage UKM</span>
            </a>
           
        </li>

        <li class="sidebar-item">
            <a href="{{ route('manage-laporan-ukm.index') }}" class="sidebar-link">
                <i class="fa-regular fa-circle-check"></i>
                <span>Laporan UKM</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('log-activity.index') }}" class="sidebar-link">
                <i class="fa-regular fa-file-lines"></i>
                <span>Log Activity</span>
            </a>
        </li>
        
        
    </ul>
    <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        
        <a href="{{ route('auth.logout') }}" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>