<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Nokuex Staff Panel') }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary-color: #292D50;
            --secondary-color: #5B8040;
            --accent-color: #DE811D;
        }
        body {
            background-color: #f5f5f5;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: var(--primary-color);
            transition: transform 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar.hidden-mobile {
            transform: translateX(-100%);
        }
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }
        .main-content.full-width {
            margin-left: 0;
        }
        .sidebar-link {
            display: block;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--accent-color);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                top: 0; /* Sidebar sits below top bar if needed, or covers it. Let's make it cover. */
                z-index: 2000;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                margin-top: 60px; /* Height of top bar */
            }
            .mobile-top-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 60px;
                background-color: var(--primary-color);
                color: white;
                padding: 0 1rem;
                z-index: 1500;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .mobile-menu-btn {
                display: flex;
                background: none;
                border: none;
                color: white;
                padding: 0;
                cursor: pointer;
            }
            .mobile-role-label {
                font-weight: 600;
                font-size: 1rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
        }
        
        /* Hide top bar on desktop */
        @media (min-width: 769px) {
            .mobile-top-bar {
                display: none;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @if(Auth::guard('staff')->check())
        <!-- Mobile Top Bar -->
        <div class="mobile-top-bar">
            <button class="mobile-menu-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
            
            @php
                $userRole = Auth::guard('staff')->user()->role ?? 'staff';
                $roleLabels = [
                    'admin' => 'Administrator',
                    'customer_care' => 'Customer Care',
                    'finance' => 'Finance',
                    'compliance' => 'Compliance',
                    'sales' => 'Sales',
                    'staff' => 'Staff',
                ];
            @endphp
            <div class="mobile-role-label">
                {{ $roleLabels[$userRole] ?? 'Staff' }}
            </div>
            
            <div style="width: 24px;"></div> <!-- Spacer for centering -->
        </div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div style="padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <h1 style="color: white; font-size: 1.25rem; font-weight: bold; margin: 0;">Nokuex Staff</h1>
                <p style="color: rgba(255,255,255,0.6); font-size: 0.875rem; margin: 0.25rem 0 0 0;">{{ Auth::guard('staff')->user()->name }}</p>
                @php
                    $userRole = Auth::guard('staff')->user()->role ?? 'staff';
                    $roleColors = [
                        'admin' => 'var(--accent-color)',
                        'customer_care' => 'var(--secondary-color)',
                        'finance' => '#4a90e2',
                        'compliance' => '#9b59b6',
                        'sales' => '#e74c3c',
                        'staff' => '#95a5a6',
                    ];
                    $roleLabels = [
                        'admin' => 'Administrator',
                        'customer_care' => 'Customer Care',
                        'finance' => 'Finance',
                        'compliance' => 'Compliance',
                        'sales' => 'Sales',
                        'staff' => 'Staff',
                    ];
                @endphp
                <span style="display: inline-block; margin-top: 0.5rem; padding: 0.25rem 0.75rem; background-color: {{ $roleColors[$userRole] ?? '#95a5a6' }}; color: white; font-size: 0.75rem; font-weight: 600; border-radius: 12px; text-transform: uppercase;">
                    {{ $roleLabels[$userRole] ?? 'Staff' }}
                </span>
            </div>
            
            
            <nav style="padding: 1rem 0;">
                @php
                    $role = Auth::guard('staff')->user()->role ?? 'staff';
                @endphp

                {{-- Admin sees everything --}}
                @if($role === 'admin' || empty($role))
                    <a href="{{ route('core.dashboard') }}" class="sidebar-link">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('staff.customers.index') }}" class="sidebar-link">
                        👥 Customers
                    </a>
                    <a href="{{ route('staff.tickets.index') }}" class="sidebar-link">
                        🎫 Support Tickets
                    </a>
                    <a href="{{ route('staff.disputes.index') }}" class="sidebar-link">
                        ⚖️ Disputes
                    </a>
                    <a href="{{ route('staff.finance.dashboard') }}" class="sidebar-link">
                        💰 Finance
                    </a>
                    <a href="{{ route('staff.sales.dashboard') }}" class="sidebar-link">
                        📈 Sales
                    </a>
                    <a href="{{ route('staff.compliance.dashboard') }}" class="sidebar-link">
                        🛡️ Compliance
                    </a>
                    <a href="{{ route('staff.chat.index') }}" class="sidebar-link">
                        💬 Chat
                    </a>
                @endif

                {{-- Customer Care --}}
                @if($role === 'customer_care')
                    <a href="{{ route('core.dashboard') }}" class="sidebar-link">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('staff.customers.index') }}" class="sidebar-link">
                        👥 Customers
                    </a>
                    <a href="{{ route('staff.tickets.index') }}" class="sidebar-link">
                        🎫 Support Tickets
                    </a>
                    <a href="{{ route('staff.disputes.index') }}" class="sidebar-link">
                        ⚖️ Disputes
                    </a>
                    <a href="{{ route('staff.chat.index') }}" class="sidebar-link">
                        💬 Chat
                    </a>
                @endif

                {{-- Finance --}}
                @if($role === 'finance')
                    <a href="{{ route('core.dashboard') }}" class="sidebar-link">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('staff.finance.dashboard') }}" class="sidebar-link">
                        💰 Finance Overview
                    </a>
                    <a href="{{ route('staff.finance.transactions') }}" class="sidebar-link">
                        📋 Transactions
                    </a>
                    <a href="{{ route('staff.finance.reconciliation') }}" class="sidebar-link">
                        🔄 Reconciliation
                    </a>
                    <a href="{{ route('staff.chat.index') }}" class="sidebar-link">
                        💬 Chat
                    </a>
                @endif

                {{-- Compliance --}}
                @if($role === 'compliance')
                    <a href="{{ route('core.dashboard') }}" class="sidebar-link">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('staff.compliance.dashboard') }}" class="sidebar-link">
                        🛡️ Compliance Overview
                    </a>
                    <a href="{{ route('staff.compliance.kyc.index') }}" class="sidebar-link">
                        📋 KYC Reviews
                    </a>
                    <a href="{{ route('staff.compliance.flags.index') }}" class="sidebar-link">
                        🚩 Compliance Flags
                    </a>
                    <a href="{{ route('staff.customers.index') }}" class="sidebar-link">
                        👥 Customers
                    </a>
                    <a href="{{ route('staff.chat.index') }}" class="sidebar-link">
                        💬 Chat
                    </a>
                @endif

                {{-- Sales --}}
                @if($role === 'sales')
                    <a href="{{ route('core.dashboard') }}" class="sidebar-link">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('staff.sales.dashboard') }}" class="sidebar-link">
                        📈 Sales Overview
                    </a>
                    <a href="{{ route('staff.sales.leads.index') }}" class="sidebar-link">
                        📋 Leads
                    </a>
                    <a href="{{ route('staff.customers.index') }}" class="sidebar-link">
                        👥 Customers
                    </a>
                    <a href="{{ route('staff.chat.index') }}" class="sidebar-link">
                        💬 Chat
                    </a>
                @endif

                {{-- Chat Support --}}
                @if($role === 'chat_support')
                    <a href="{{ route('core.dashboard') }}" class="sidebar-link">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('staff.tickets.index') }}" class="sidebar-link">
                        🎫 Support Tickets
                    </a>
                    <a href="{{ route('staff.chat.index') }}" class="sidebar-link">
                        💬 Chat
                    </a>
                @endif
            </nav>

            <div style="position: absolute; bottom: 0; width: 100%; padding: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <form method="POST" action="{{ route('core.logout') }}">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 0.75rem; background-color: var(--accent-color); color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
    @endif

    <!-- Flash Messages -->
    @if(session('success'))
        <div style="max-width: 1200px; margin: 1rem auto; padding: 0 1rem;">
            <div style="background-color: #d4edda; border: 1px solid var(--secondary-color); color: var(--secondary-color); padding: 1rem; border-radius: 4px;">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div style="max-width: 1200px; margin: 1rem auto; padding: 0 1rem;">
            <div style="background-color: #f8d7da; border: 1px solid var(--accent-color); color: var(--accent-color); padding: 1rem; border-radius: 4px;">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @yield('content')

    @if(Auth::guard('staff')->check())
        </div>
    @endif

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            sidebar.classList.toggle('active');
            
            // Close sidebar when clicking outside on mobile
            if (sidebar.classList.contains('active')) {
                document.addEventListener('click', function closeSidebar(e) {
                    if (!sidebar.contains(e.target) && !menuBtn.contains(e.target)) {
                        sidebar.classList.remove('active');
                        document.removeEventListener('click', closeSidebar);
                    }
                });
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
