<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nokuex Staff Panel</title>
    <style>
        :root {
            --primary-color: #292D50;
            --secondary-color: #5B8040;
            --accent-color: #DE811D;
            --sidebar-width: 250px;
            --sidebar-collapsed: 70px;
            --topbar-height: 60px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Layout Structure */
        .layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary-color);
            color: white;
            transition: all 0.3s ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-brand {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .menu-item {
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .menu-section {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1rem;
        }
        
        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--accent-color);
        }
        
        .menu-item i {
            width: 20px;
            text-align: center;
        }
        
        /* Menu Groups and Submenus */
        .menu-group {
            position: relative;
        }
        
        .menu-header {
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .menu-header:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .menu-group.active .menu-header {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--accent-color);
        }
        
        .submenu {
            overflow: visible;
            background: rgba(0,0,0,0.2);
        }
        
        .submenu-item {
            display: block;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .submenu-item:hover, .submenu-item.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--secondary-color);
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
        }
        
        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .menu-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: var(--primary-color);
            display: none;
        }
        
        .topbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--secondary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* Content Area */
        .content {
            padding: 1.5rem;
            min-height: calc(100vh - var(--topbar-height));
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        /* Buttons */
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-success {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-warning {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        /* Badges */
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-warning {
            background-color: var(--accent-color);
            color: white;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-color);
        }
        
        .stat-card.success {
            border-left-color: var(--secondary-color);
        }
        
        .stat-card.warning {
            border-left-color: var(--accent-color);
        }
        
        .stat-card.info {
            border-left-color: #17a2b8;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(41, 45, 80, 0.2);
        }
        
        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            .sidebar.mobile-open {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .topbar-title {
                font-size: 1.1rem;
            }
            
            .content {
                padding: 1rem;
            }
            
            .card {
                padding: 1rem;
            }
            
            .submenu-item {
                padding: 0.5rem 1rem 0.5rem 2rem;
            }
        }
        
        /* Mobile overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        @media (max-width: 768px) {
            .sidebar.mobile-open + .sidebar-overlay {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <!-- Fixed: Logo links to user's department dashboard -->
                <a href="{{ Auth::guard('staff')->check() ? Auth::guard('staff')->user()->getDashboardUrl() : route('core.dashboard') }}" class="sidebar-brand">Nokuex</a>
            </div>
            <nav class="sidebar-menu">
                @php
                    $user = Auth::guard('staff')->user();
                    $isAdmin = $user && $user->department() === \Modules\Core\Entities\Role::DEPARTMENT_ADMIN;
                @endphp

                @if($isAdmin)
                <a href="{{ route('core.dashboard') }}" class="menu-item {{ request()->routeIs('core.dashboard') ? 'active' : '' }}">
                    <i>📊</i> Dashboard
                </a>
                @endif
                
                @php
                    $hasAnyDepartment = $user && (
                        $user->hasPermission('customercare.dashboard.view') ||
                        $user->hasPermission('sales.dashboard.view') || 
                        $user->hasPermission('finance.dashboard.view') ||
                        $user->hasPermission('compliance.dashboard.view')
                    );
                @endphp

                @if($hasAnyDepartment)
                <div class="menu-section">Departments</div>
                @endif
            

                @if($user && $user->hasPermission('customercare.dashboard.view'))
                <div class="menu-group {{ request()->routeIs('customercare.*') ? 'active' : '' }}">
                    <div class="menu-header">
                        <i>🎯</i> Customer Care
                    </div>
                    <div class="submenu">
                        <a href="{{ route('customercare.dashboard') }}" class="submenu-item {{ request()->routeIs('customercare.dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        @if($user->hasPermission('customercare.crm.access'))
                        <a href="{{ route('customercare.crm') }}" class="submenu-item {{ request()->routeIs('customercare.crm*') ? 'active' : '' }}">
                            CRM System
                        </a>
                        @endif
                        @if($user->hasPermission('customercare.tickets.access'))
                        <a href="{{ route('customercare.tickets') }}" class="submenu-item {{ request()->routeIs('customercare.tickets*') ? 'active' : '' }}">
                            Support Tickets
                        </a>
                        @endif
                        @if($user->hasPermission('customercare.disputes.access'))
                        <a href="{{ route('customercare.disputes') }}" class="submenu-item {{ request()->routeIs('customercare.disputes*') ? 'active' : '' }}">
                            Dispute Resolution
                        </a>
                        @endif
                    </div>
                </div>
                @endif


                @can('sales.dashboard.view')
                <div class="menu-group {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <div class="menu-header">
                        <i>💰</i> Sales
                    </div>
                    <div class="submenu">
                        <a href="{{ route('sales.dashboard') }}" class="submenu-item {{ request()->routeIs('sales.dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('sales.leads') }}" class="submenu-item {{ request()->routeIs('sales.leads*') ? 'active' : '' }}">
                            Lead Management
                        </a>
                        <a href="{{ route('sales.performance') }}" class="submenu-item {{ request()->routeIs('sales.performance*') ? 'active' : '' }}">
                            Performance Tracking
                        </a>
                        <a href="{{ route('sales.followups') }}" class="submenu-item {{ request()->routeIs('sales.followups*') ? 'active' : '' }}">
                            Follow-Up System
                        </a>
                    </div>
                </div>
                @endcan

                @can('finance.dashboard.view')
                <div class="menu-group {{ request()->routeIs('finance.*') ? 'active' : '' }}">
                    <div class="menu-header">
                        <i>💳</i> Finance
                    </div>
                    <div class="submenu">
                        <a href="{{ route('finance.dashboard') }}" class="submenu-item {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('finance.transactions') }}" class="submenu-item {{ request()->routeIs('finance.transactions*') ? 'active' : '' }}">
                            Transaction Monitoring
                        </a>
                        <a href="{{ route('finance.reports') }}" class="submenu-item {{ request()->routeIs('finance.reports*') ? 'active' : '' }}">
                            Financial Reports
                        </a>
                        <a href="{{ route('finance.reconciliation') }}" class="submenu-item {{ request()->routeIs('finance.reconciliation*') ? 'active' : '' }}">
                            Reconciliation
                        </a>
                        <a href="{{ route('finance.blusalt') }}" class="submenu-item {{ request()->routeIs('finance.blusalt*') ? 'active' : '' }}">
                            Blusalt Integration
                        </a>
                    </div>
                </div>
                @endcan

                @can('compliance.dashboard.view')
                <div class="menu-group {{ request()->routeIs('compliance.*') ? 'active' : '' }}">
                    <div class="menu-header">
                        <i>🛡️</i> Compliance
                    </div>
                    <div class="submenu">
                        <a href="{{ route('compliance.dashboard') }}" class="submenu-item {{ request()->routeIs('compliance.dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('compliance.freeze') }}" class="submenu-item {{ request()->routeIs('compliance.freeze*') ? 'active' : '' }}">
                            Account Freeze/Unfreeze
                        </a>
                        <a href="{{ route('compliance.otc') }}" class="submenu-item {{ request()->routeIs('compliance.otc*') ? 'active' : '' }}">
                            OTC Trade Monitoring
                        </a>
                        <a href="{{ route('compliance.kyc') }}" class="submenu-item {{ request()->routeIs('compliance.kyc*') ? 'active' : '' }}">
                            KYC Case Management
                        </a>
                        <a href="{{ route('compliance.kyb') }}" class="submenu-item {{ request()->routeIs('compliance.kyb*') ? 'active' : '' }}">
                            KYB Case Management
                        </a>
                        <a href="{{ route('compliance.flagging') }}" class="submenu-item {{ request()->routeIs('compliance.flagging*') ? 'active' : '' }}">
                            Automated Flagging
                        </a>
                    </div>
                </div>
                @endcan

                <div class="menu-section">System</div>

                @can('core.staff.view')
                <a href="{{ route('core.staff.index') }}" class="menu-item {{ request()->routeIs('core.staff.*') ? 'active' : '' }}">
                    <i>👥</i> Staff Management
                </a>
                @endcan

                @can('core.role.view')
                <a href="{{ route('core.role.index') }}" class="menu-item {{ request()->routeIs('core.role.*') ? 'active' : '' }}">
                    <i>🔐</i> Role Management
                </a>
                @endcan

                @can('core.notification.view')
                <a href="{{ route('core.notification.index') }}" class="menu-item {{ request()->routeIs('core.notification.*') ? 'active' : '' }}">
                    <i>🔔</i> Notifications
                </a>
                @endcan

                @can('chat.access')
                <a href="{{ route('chat.index') }}" class="menu-item {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                    <i>💬</i> Chat
                </a>
                @endcan
            </nav>
        </aside>

        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" id="menuToggle">
                        ☰
                    </button>
                    <h1 class="topbar-title">@yield('title', 'Dashboard')</h1>
                </div>
                
                <div class="topbar-right">
                    @auth('staff')
                    <div class="user-menu" id="userMenu">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::guard('staff')->user()->name, 0, 1)) }}
                        </div>
                        <span>{{ Auth::guard('staff')->user()->name }}</span>
                    </div>
                    
                    <form id="logout-form" action="{{ route('core.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    @endauth
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                @if(session('success'))
                <div class="card" style="background: var(--secondary-color); color: white; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="card" style="background: #fee; color: #c33; margin-bottom: 1rem;">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- User Dropdown Menu -->
    <div id="userDropdown" style="display: none; position: absolute; top: 70px; right: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); z-index: 1000; min-width: 200px;">
        <div style="padding: 1rem; border-bottom: 1px solid #e0e0e0;">
            <div style="font-weight: 600;">{{ Auth::guard('staff')->user()->name ?? '' }}</div>
            <div style="font-size: 0.8rem; color: #666;">{{ Auth::guard('staff')->user()->email ?? '' }}</div>
        </div>
        <div style="padding: 0.5rem;">
            <button type="button" onclick="document.getElementById('logout-form').submit()" class="btn btn-warning" style="width: 100%; justify-content: center;">
                Logout
            </button>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('sidebar').classList.toggle('mobile-open');
        });

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('mobile-open');
        });

        // User menu dropdown
        document.getElementById('userMenu').addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            const dropdown = document.getElementById('userDropdown');
            const userMenu = document.getElementById('userMenu');
            
            // Close sidebar when clicking outside on mobile
            if (window.innerWidth <= 768 && 
                sidebar.classList.contains('mobile-open') && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target)) {
                sidebar.classList.remove('mobile-open');
            }
            
            // Close user dropdown when clicking outside
            if (!userMenu.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Auto-hide sidebar on mobile after clicking a link
        const menuItems = document.querySelectorAll('.menu-item, .submenu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('mobile-open');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>