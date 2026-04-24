<aside class="sidebar bg-dark text-white vh-100">
    <nav class="nav flex-column">
        <!-- Dashboard -->
        <a class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i>
            <span>Dashboard</span>
        </a>
        
        @can('view_servers')
        <!-- Servers -->
        <a class="nav-link {{ request()->routeIs('servers*') ? 'active' : '' }}" href="{{ route('servers.index') }}">
            <i class="fas fa-server me-2"></i>
            <span>Máy chủ</span>
        </a>
        @endcan
        
        @can('view_criteria')
        <!-- Criteria -->
        <a class="nav-link {{ request()->routeIs('criteria*') ? 'active' : '' }}" href="{{ route('criteria.index') }}">
            <i class="fas fa-list me-2"></i>
            <span>Tiêu chí</span>
        </a>
        @endcan
        
        @can('run_assessments')
        <!-- Assessments -->
        <a class="nav-link {{ request()->routeIs('assessments*') ? 'active' : '' }}" href="{{ route('assessments.index') }}">
            <i class="fas fa-clipboard-list me-2"></i>
            <span>Đánh giá</span>
        </a>
        @endcan
        
        @can('view_files')
        <!-- Assessment Files -->
        <a class="nav-link {{ request()->routeIs('files*') ? 'active' : '' }}" href="{{ route('files.index') }}">
            <i class="fas fa-folder me-2"></i>
            <span>Tệp đánh giá</span>
        </a>
        @endcan
        
        @can('view_reports')
        <!-- Reports -->
        <a class="nav-link {{ request()->routeIs('reports*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
            <i class="fas fa-chart-line me-2"></i>
            <span>Báo cáo</span>
        </a>
        @endcan
        
        @can('view_audit')
        <!-- Audit Logs -->
        <a class="nav-link {{ request()->routeIs('audit*') ? 'active' : '' }}" href="{{ route('audit.index') }}">
            <i class="fas fa-history me-2"></i>
            <span>Audit Logs</span>
        </a>
        @endcan
        
        @can('manage_users')
        <!-- Users (Super Admin & Admin only) -->
        <hr class="my-3">
        <div class="nav-section-title px-3">QUẢN TRỊ</div>
        <a class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <i class="fas fa-users me-2"></i>
            <span>Người dùng</span>
        </a>
        <a class="nav-link {{ request()->routeIs('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
            <i class="fas fa-shield-alt me-2"></i>
            <span>Vai trò</span>
        </a>
        @endcan
        
        @can('manage_settings')
        <!-- Settings -->
        <a class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
            <i class="fas fa-cog me-2"></i>
            <span>Cài đặt</span>
        </a>
        @endcan
    </nav>
</aside>

<style>
.sidebar {
    width: 280px;
    transition: width 0.3s ease;
    overflow-x: hidden;
}

.sidebar.collapsed {
    width: 70px;
}

.sidebar.collapsed .nav-link span,
.sidebar.collapsed .nav-section-title {
    display: none;
}

.sidebar.collapsed .nav-link i {
    margin-right: 0;
    font-size: 1.2rem;
}

.sidebar .nav-link {
    color: #adb5bd;
    padding: 0.75rem 1rem;
    transition: all 0.2s;
}

.sidebar .nav-link:hover {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar .nav-link.active {
    color: #fff;
    background-color: #0d6efd;
}

.sidebar .nav-section-title {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: #6c757d;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}
</style>